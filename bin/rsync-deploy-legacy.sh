#!/bin/bash

if [ -z "${SOURCE_DIRECTORY}" ]; then
  SOURCE_DIRECTORY="$(git rev-parse --show-toplevel)"
fi
if [ -z "${TARGET_DIRECTORY}" ]; then
  TARGET_DIRECTORY="/var/www/api.carlbennett.me"
fi

set -e

printf "[1/3] Generating version information...\n"
# Version identifier
printf "$(git describe --always --tags)\n" \
  > ${SOURCE_DIRECTORY}/etc/.rsync-version
# Version hash
printf "$(git rev-parse HEAD)\n" \
  >> ${SOURCE_DIRECTORY}/etc/.rsync-version
# Version ISO8601 timestamp
printf "$(git log -n 1 --pretty='%aI' HEAD)\n" \
  >> ${SOURCE_DIRECTORY}/etc/.rsync-version
# LICENSE version and ISO8601 timestamp
printf "$(git log -n 1 --pretty='%h %aI' ./LICENSE.txt)" \
  >> ${SOURCE_DIRECTORY}/etc/.rsync-version

printf "[2/3] Syncing...\n"
rsync -avzc --delete --delete-excluded --delete-after --progress \
  --exclude-from="${SOURCE_DIRECTORY}/etc/rsync-exclude.txt" \
  --chown=nginx:nginx --rsync-path="sudo rsync" \
  "${SOURCE_DIRECTORY}/" "${TARGET_DIRECTORY}"

printf "[3/3] Post-deploy clean up...\n"
rm ${SOURCE_DIRECTORY}/etc/.rsync-version

printf "Operation complete!\n"
