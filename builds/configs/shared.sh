#!/usr/bin/env bash

# modify if need.
export BASE_GCR_REPO="asia-east1-docker.pkg.dev/sevensenses-auto-bot/utils"
export BASE_APP_IMAGE_NAME="metasens-users-app"
export BASE_APP_IMAGE_VERSION="1.2.0"
export BASE_CSPELL_IMAGE_NAME="cspell-checker"
export BASE_CSPELL_IMAGE_VERSION="1.0.1"

# shared config sections.
export APP_DOCKER_IMAGE="${BASE_GCR_REPO}/${BASE_APP_IMAGE_NAME}:${BASE_APP_IMAGE_VERSION}"
export CSPELL_DOCKER_IMAGE="${BASE_GCR_REPO}/${BASE_CSPELL_IMAGE_NAME}:${BASE_CSPELL_IMAGE_VERSION}"
export DEPLOY_APP_DOCKER_IMAGE="${RELEASE_GCR_REPO}/${APP_NAME}-app-deploy"
