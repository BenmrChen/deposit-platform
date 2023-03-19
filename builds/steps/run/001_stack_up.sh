#!/usr/bin/env bash

if [[ ! -f "${PARENT_DIR}/.env" ]]; then
    /bin/cp -f "${PARENT_DIR}/.env.example" "${PARENT_DIR}/.env"
fi

compose_set_env "run"
compose_cmd build --pull
compose_cmd up -d mysql redis mailhog
