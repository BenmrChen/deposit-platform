#!/usr/bin/env bash

compose_run_cmd app composer install
compose_cmd up -d app
compose_exec_cmd app php artisan migrate
compose_exec_cmd app php artisan make:api-docs --scheme=http
compose_cmd up -d swagger-ui

echo ""
echo "your docker stack is ready."
echo "api docs: http://localhost:8080"
