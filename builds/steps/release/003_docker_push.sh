#!/usr/bin/env bash

if [[ -n "${RELEASE_PUSH_GCR_OAUTH_TOKEN}" ]]; then
    docker_gcr_login "${RELEASE_PUSH_GCR_OAUTH_TOKEN}" "${RELEASE_GCR_REPO}"
fi

if [[ ! "${RELEASE_PUSH_DISABLED}" == "true" ]]; then
    echo "Begin to push app deployable image to gcr." && echo ""
    compose_cmd push app-deploy
else
    echo "skip due to RELEASE_PUSH_DISABLED flag is true."
fi
