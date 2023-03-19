#!/usr/bin/env bash

compose_set_env "code-quality"

compose_cmd build --pull --progress plain
docker_clean_build_cache
compose_cmd up -d app mysql redis cspell
