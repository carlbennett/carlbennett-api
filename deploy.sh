#!/bin/sh

DEPLOY_SOURCE="/home/nginx/carlbennett-api-local/"
DEPLOY_TARGET="/home/nginx/carlbennett-api/"

printf "== Deploying api.carlbennett.me ================================================\n"

printf "Source: $DEPLOY_SOURCE\n"
printf "Target: $DEPLOY_TARGET\n"

printf "==== Syncing source code =======================================================\n"
rsync --rsync-path='sudo rsync' -Oprtvz --delete --exclude='.git/' --exclude='.gitignore' --exclude='deploy.sh' --exclude 'README.md' --exclude='settings.sample.json' "$DEPLOY_SOURCE" "carl@carlbennett.me:$DEPLOY_TARGET"

printf "==== Changing user and group ownership =========================================\n"
ssh carl@carlbennett.me sudo chown nginx:webusers -Rv /home/nginx/carlbennett-api | grep -v "^ownership of .* retained as .*$"

printf "== Deployments done ============================================================\n"
