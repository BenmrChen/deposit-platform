#!/usr/bin/env bash

CLEAN_ALL="${CLEAN_ALL:+true}"
EXEC_STEP="${1:-run}"

compose_set_env "${EXEC_STEP}"

if [[ "${CLEAN_ALL}" == "true" ]]; then
    compose_cmd down -v
else
    compose_cmd down
fi
