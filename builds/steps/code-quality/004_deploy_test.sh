#!/usr/bin/env bash

docker_cmd exec "${APP_ID}" php artisan config:cache || EXIT_CODE=$((EXIT_CODE + 1))
docker_cmd exec "${APP_ID}" php artisan route:cache || EXIT_CODE=$((EXIT_CODE + 1))
