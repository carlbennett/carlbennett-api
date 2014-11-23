#!/bin/sh

printf "== Deploying to api.carlbennett.me =============================================\n"

printf "==== Syncing source code =======================================================\n"
rsync -Oprtvz --exclude='.git/' --exclude='.gitignore' --exclude='deploy.sh' --exclude 'README.md' --exclude='settings.sample.json' . root@carlbennett.me:/home/nginx/carlbennett-api
printf "==== Changing user and group ownership =========================================\n"
ssh root@carlbennett.me chown nginx:webusers -Rv /home/nginx/carlbennett-api | grep -v "^ownership of .* retained as .*$"

printf "== Deployments done ============================================================\n"
