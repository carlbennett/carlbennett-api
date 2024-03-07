#!/usr/bin/env bash

set -euo pipefail

git --version >/dev/null || {
  printf "Git is not installed on the PATH or this system.\n" 1>&2
  exit 1
}

# Version identifier
printf "$(git describe --always --tags)\n"
# Version hash
printf "$(git rev-parse HEAD)\n"
# Version ISO8601 timestamp
printf "$(git log -n 1 --pretty='%aI' HEAD)\n"
# LICENSE version and ISO8601 timestamp
printf "$(git log -n 1 --pretty='%h %aI' ./LICENSE.txt)"
