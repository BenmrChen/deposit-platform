#!/usr/bin/env bash

function ci_cspell_prepare {
    local app_id
    app_id="$(compose_get_container_id cspell | tail -1)"

    docker_cmd cp app "${app_id}:${WORKSPACE_DIR}"
    docker_cmd cp database "${app_id}:${WORKSPACE_DIR}"
    docker_cmd cp tests "${app_id}:${WORKSPACE_DIR}"
    docker_cmd cp Modules "${app_id}:${WORKSPACE_DIR}"
    docker_cmd cp cspell-extra-words.txt "${app_id}:/etc/cspell/applicationExtraWords.txt"

    docker_cmd exec -u "root:root" "${app_id}" chown -R "${COMPOSE_USER}:${COMPOSE_USER}" "${WORKSPACE_DIR}"
}

function ci_cspell_check {
    compose_exec_cmd cspell cspell-x lint "./app/**/*" "./database/factories/*" "./database/migrations/*" "./tests/**/*" "./Modules/**/*"
}

function ci_php_cs_fixer_check {
    composer_run_vendor_cmd php-cs-fixer fix --dry-run --show-progress=dots -v
}
