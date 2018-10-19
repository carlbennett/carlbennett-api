#!/bin/bash

if [ -z "${SOURCE_DIRECTORY}" ]; then
  SOURCE_DIRECTORY="$(git rev-parse --show-toplevel)"
fi
if [ -z "${TARGET_DIRECTORY}" ]; then
  TARGET_DIRECTORY="/var/www/api.carlbennett.me"
fi

set -e

printf "[1/4] Getting version identifier of this deploy...\n"
DEPLOY_VERSION="$(git describe --always --tags)"

printf "[2/4] Building version information into this deploy...\n"
printf "${DEPLOY_VERSION}" > ${SOURCE_DIRECTORY}/etc/.rsync-version

printf "[3/4] Syncing...\n"
rsync -avzc --delete --delete-excluded --delete-after --progress \
  --exclude-from="${SOURCE_DIRECTORY}/etc/rsync-exclude.txt" \
  --chown=nginx:nginx --rsync-path="sudo rsync" \
  "${SOURCE_DIRECTORY}/" "${TARGET_DIRECTORY}"

printf "[4/4] Post-deploy clean up...\n"
rm ${SOURCE_DIRECTORY}/etc/.rsync-version

printf "Operation complete!\n"
