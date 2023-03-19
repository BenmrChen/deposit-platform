#!/usr/bin/env bash

function exec_cmd_with_help_msg {
    local status_code
    echo "[code-quality] begin to running ${1} check"
    shift
    echo ""
    "${@}"
    status_code=$?
    echo ""
    return ${status_code}
}

function load_dot_env_file {
    local dot_env_file="${PARENT_DIR}/.env.example"
    local dot_env_arr
    local dot_env_items

    if [[ ! -f "${dot_env_file}" ]]; then
        echo "dot env file is not exists. exit."
        exit 1
    fi

    mapfile -t dot_env_arr < "${dot_env_file}"

    for env_str in "${dot_env_arr[@]}"; do
        IFS='=' read -ra dot_env_items <<< "${env_str}"
        if [[ ${#dot_env_items[@]} -eq 2 ]] && \
           [[ -z "${!dot_env_items[0]^^}" ]] && \
           [[ -n "${dot_env_items[1]}" ]]; then
            eval "export ${dot_env_items[0]^^}=${dot_env_items[1]}"
        fi
    done
}

function generate_random_id {
    local index
    local chars="abcdefghijklmnopqrstuwvxyz1234567890"

    # shellcheck disable=SC2034
    for index in {1..8}; do
        echo -n "${chars:RANDOM%${#chars}:1}"
    done
}

function export_nfs_mount_dir {
    export NFS_EXPORT_DIR="${HOME}"
    export NFS_DOCKER_VOLUME_DIR="${NFS_EXPORT_DIR}"

    # Catalina nfs new design ... need workaround.
    # https://github.com/drud/ddev/issues/1869
    if [[ -d "/System/Volumes/Data" ]]; then
        NFS_DOCKER_VOLUME_DIR="/System/Volumes/Data${NFS_EXPORT_DIR}"
    fi
}

function is_in_array {
  local match="${1}"
  local -n search_arr=${2}
  for val in "${search_arr[@]}"; do
    [[ "${val}" == "${match}" ]] && return 0
  done
  return 1
}

function not_execute_in_docker {
    if [[ -f "/.dockerenv" ]]; then
        echo "this command can not execute in docker container. exit."
        exit 1
    fi
}
