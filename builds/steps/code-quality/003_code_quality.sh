#!/usr/bin/env bash

export EXIT_CODE=0
export REPORT_NAMES_ARR=()

exec_cmd_with_help_msg phplint composer_run_vendor_cmd phplint || EXIT_CODE=$((EXIT_CODE + 1))
exec_cmd_with_help_msg ci_php_cs_fixer_check || EXIT_CODE=$((EXIT_CODE + 1))
exec_cmd_with_help_msg phpcs composer_run_vendor_cmd phpcs || EXIT_CODE=$((EXIT_CODE + 1))
exec_cmd_with_help_msg cspell ci_cspell_check || EXIT_CODE=$((EXIT_CODE + 1))
exec_cmd_with_help_msg phpstan composer_run_vendor_cmd phpstan analyse --memory-limit=512M --no-progress || EXIT_CODE=$((EXIT_CODE + 1))

if ! exec_cmd_with_help_msg phpunit composer_run_vendor_cmd phpunit; then
    EXIT_CODE=$((EXIT_CODE + 1))
else
    REPORT_NAMES_ARR+=("phpunit")
fi

# copy reports
for report in "${REPORT_NAMES_ARR[@]}"; do
    LOCAL_ARTIFACT_DIR="${PARENT_DIR}/${ARTIFACT_RELATIVE_DIR}/"
    LOCAL_REPORT_DIR="${LOCAL_ARTIFACT_DIR}/${report}"
    DOCKER_REPORT_DIR="${WORKSPACE_DIR}/${ARTIFACT_RELATIVE_DIR}/${report}"

    [[ -d "${LOCAL_REPORT_DIR}" ]] && rm -rf "${LOCAL_REPORT_DIR}"
    docker_cmd cp "${APP_ID}:${DOCKER_REPORT_DIR}" "${LOCAL_ARTIFACT_DIR}"
done
