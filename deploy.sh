#!/bin/bash
printf "===============================================================================\n"

printf "== Configuring environment ====================================================\n"
DEPLOY_SOURCE_PATH=$(git rev-parse --show-toplevel)
DEPLOY_TARGET_HOST="fc19.togglebox.carlbennett.me"
DEPLOY_TARGET_USER="carl"
DEPLOY_TARGET_PATH="/home/nginx/carlbennett-api"

printf "DEPLOY_SOURCE_PATH = ${DEPLOY_SOURCE_PATH}\n"
printf "DEPLOY_TARGET_HOST = ${DEPLOY_TARGET_HOST}\n"
printf "DEPLOY_TARGET_USER = ${DEPLOY_TARGET_USER}\n"
printf "DEPLOY_TARGET_PATH = ${DEPLOY_TARGET_PATH}\n"

printf "== Sanity check ===============================================================\n"
[ "$DEPLOY_SOURCE_PATH" == "" ] && \
  printf "Error: DEPLOY_SOURCE_PATH is empty.\n" && exit 1
[ "$DEPLOY_TARGET_HOST" == "" ] && \
  printf "Error: DEPLOY_TARGET_HOST is empty.\n" && exit 1
[ "$DEPLOY_TARGET_USER" == "" ] && \
  printf "Error: DEPLOY_TARGET_USER is empty.\n" && exit 1
[ "$DEPLOY_TARGET_PATH" == "" ] && \
  printf "Error: DEPLOY_TARGET_PATH is empty.\n" && exit 1

printf "== Deploying to target ========================================================\n"
CUR_DIR=`pwd` && cd "$DEPLOY_SOURCE_PATH"
CODE="$?" && [ "$CODE" -ne 0 ] && exit "$CODE"

rsync --rsync-path="sudo rsync" -Oprtvz --delete \
  --exclude-from="rsync-exclude.txt" \
  "${DEPLOY_SOURCE_PATH}/" \
  "${DEPLOY_TARGET_USER}@${DEPLOY_TARGET_HOST}:${DEPLOY_TARGET_PATH}/"
CODE="$?" && [ "$CODE" -ne 0 ] && exit "$CODE"

cd "$CUR_DIR"
CODE="$?" && [ "$CODE" -ne 0 ] && exit "$CODE"

printf "== Changing permissions on target =============================================\n"
ssh "${DEPLOY_TARGET_USER}@${DEPLOY_TARGET_HOST}" \
  sudo chown nginx:webusers -Rv "$DEPLOY_TARGET_PATH" | \
  grep -v "^ownership of .* retained as .*$"
CODE="$?" && [ "$CODE" -ne 0 ] && exit "$CODE"

printf "===============================================================================\n"
exit 0
