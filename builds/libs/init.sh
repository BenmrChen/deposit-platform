#!/usr/bin/env bash

# shellcheck disable=SC1090,SC1091
source "${PARENT_DIR}/builds/libs/shared_funcs.sh"

# shellcheck disable=SC1090,SC1091
source "${PARENT_DIR}/builds/libs/docker.sh"

# shellcheck disable=SC1090,SC1091
source "${PARENT_DIR}/builds/libs/cicd.sh"

export WORKSPACE_DIR="${PARENT_DIR}"
export DOCKER_FILE_DIR="${PARENT_DIR}/builds/docker"
export ARTIFACT_RELATIVE_DIR="builds/artifacts"

# init steps
export CI_MODE_TASKS=("code-quality" "release")
if is_in_array "${TASK_SECTION}" CI_MODE_TASKS; then
    export CI_MODE="true"
    export CI_RANDOM_ID
    CI_RANDOM_ID="$(generate_random_id)"
    WORKSPACE_DIR="/getoken_code"
fi

load_dot_env_file
export_nfs_mount_dir

# shellcheck disable=SC1090,SC1091
source "${PARENT_DIR}/builds/configs/shared.sh"

if [[ -f "${PARENT_DIR}/builds/configs/user_defined.sh" ]]; then
    source "${PARENT_DIR}/builds/configs/user_defined.sh"
fi

if [[ -z "${GITHUB_TOKEN}" ]]; then
    echo "GITHUB_TOKEN env is empty. exit."
    exit 1
fi

export COMPOSER_AUTH="{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}"
