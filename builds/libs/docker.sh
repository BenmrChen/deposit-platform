#!/usr/bin/env bash

function compose_set_env {
    export COMPOSE_DOCKER_CLI_BUILD=1
    export DOCKER_BUILDKIT=1

    case "${1}" in
        "run")
            export COMPOSE_FILE="${DOCKER_FILE_DIR}/docker-compose.yml:${DOCKER_FILE_DIR}/docker-compose.local.yml"
            export COMPOSE_IGNORE_ORPHANS=0
            ;;
        "code-quality")
            export COMPOSE_FILE="${DOCKER_FILE_DIR}/docker-compose.yml:${DOCKER_FILE_DIR}/docker-compose.ci.yml"
            export COMPOSE_USER="www-data"
            export COMPOSE_PROJECT_NAME="${CI_RANDOM_ID}"
            export APP_CI_DOCKER_IMAGE="${BASE_GCR_REPO}/${APP_NAME}-app-ci"
            export APP_CI_DOCKER_VERSION="${CI_RANDOM_ID}"
            ;;
        "release")
            export COMPOSE_FILE="${DOCKER_FILE_DIR}/docker-compose.release.yml"
            ;;
    esac
}

function compose_cmd {
    local cmd=()
    local args=("${@}")
    local project_name

    project_name="${PARENT_DIR##*/}"
    project_name="${project_name//-/_}"

    if [[ "${CI_MODE}" == "true" ]]; then
        project_name="${project_name}_${CI_RANDOM_ID}"
        cmd+=("docker-compose")
    else
        cmd+=("mutagen-compose")
    fi

    cmd+=("--project-directory" "${PARENT_DIR}" "--project-name" "${project_name}")

    "${cmd[@]}" "${@}"
}

function compose_run_cmd {
    local extra_opts=("-T")

    if [[ -n "${COMPOSE_USER}" ]]; then
        extra_opts+=("-u" "${COMPOSE_USER}")
    fi

    compose_cmd run --rm "${extra_opts[@]}" "${@}"
}

function compose_exec_cmd {
    local extra_opts=("-T")

    if [[ -n "${COMPOSE_USER}" ]]; then
        extra_opts+=("-u" "${COMPOSE_USER}")
    fi

    compose_cmd exec "${extra_opts[@]}" "${@}"
}

function compose_get_container_id {
    compose_cmd ps -q "${1}"
}

function composer_run_vendor_cmd {
    local cmd=()
    local vendor_bin_dir="${WORKSPACE_DIR}/vendor/bin"
    local section

    cmd+=("compose_exec_cmd" "app")

    section="${1}"; shift
    case "${section}" in
        "phpcs") cmd+=("${vendor_bin_dir}/phpcs" "${@}");;
        "php-cs-fixer") cmd+=("${vendor_bin_dir}/php-cs-fixer" "${@}");;
        "phpcbf") cmd+=("${vendor_bin_dir}/phpcbf" "${@}");;
        "phplint") cmd+=("${vendor_bin_dir}/phplint" "${@}");;
        "phpunit") cmd+=("${vendor_bin_dir}/phpunit" "${@}");;
        "phpstan") cmd+=("${vendor_bin_dir}/phpstan" "${@}");;
    esac

    "${cmd[@]}"
}

function docker_cmd {
    local cmd=()

    cmd+=("docker")

    if [[ -n "${CUSTOM_DOCKER_CONFIG}" ]]; then
        cmd+=("--config" "${CUSTOM_DOCKER_CONFIG}")
    fi

    "${cmd[@]}" "${@}"
}

function docker_clean_build_cache {
    docker system prune -f
}

function docker_gcr_login {
    local ouath_token="${1}"
    local login_url="${2}"
    echo "${ouath_token}" | \
    docker login -u oauth2accesstoken --password-stdin "https://${login_url}"
}
