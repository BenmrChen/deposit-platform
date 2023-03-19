#!/usr/bin/env bash

set -eo pipefail
shopt -s extglob

export PARENT_DIR

EXEC_PATH="${BASH_SOURCE[0]}"
[ -L "${BASH_SOURCE[0]}" ] && EXEC_PATH="$(readlink "${BASH_SOURCE[0]}")"
PARENT_DIR="$(dirname "${EXEC_PATH}")"
PARENT_DIR="$(cd "${PARENT_DIR}" && cd ../ && pwd)"

export TASK_SECTION="${1}"
shift

# shellcheck disable=SC1090
source "${PARENT_DIR}/builds/libs/init.sh"

echo "running some tasks for ci / cd flow."
echo ""

SCRIPT_FOLDER="${PARENT_DIR}/builds/steps/${TASK_SECTION}"
if [[ ! -d "${SCRIPT_FOLDER}" ]]; then
    echo "task section folder is not exists. exit."
    exit 1
fi

for task in "${SCRIPT_FOLDER}"/*.sh; do
    task_name="$(basename "${task}")"
    echo "running task ${task_name:0:-3} ..."
    echo ""
    # shellcheck disable=SC1090
    source "${task}"
    echo ""
done

exit 0
