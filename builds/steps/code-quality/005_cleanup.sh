#!/usr/bin/env bash

compose_cmd down -v

echo ""
echo "code quality check finished."
echo "execute status: ${EXIT_CODE}"

# shellcheck disable=SC2086
exit ${EXIT_CODE}
