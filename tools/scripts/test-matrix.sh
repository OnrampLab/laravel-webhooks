#!/usr/bin/env bash
# Run one matrix lane locally inside the same cimg/php:<version> image CI uses.
#
# Usage: tools/scripts/test-matrix.sh <php-version> <laravel-constraint> [lowest] [-- <command...>]
#   e.g. tools/scripts/test-matrix.sh 8.1 '^10.0'
#        tools/scripts/test-matrix.sh 8.0 '^8.0' lowest
#        LINT=1 tools/scripts/test-matrix.sh 8.4 '^13.0' -- vendor/bin/phpstan
#
# The repository is copied inside the container, so the local composer.json
# and vendor/ are never touched. Default command: vendor/bin/phpunit
set -euo pipefail

PHP_VERSION="${1:?usage: test-matrix.sh <php-version> <laravel-constraint> [lowest] [-- <command...>]}"
LARAVEL="${2:?usage: test-matrix.sh <php-version> <laravel-constraint> [lowest] [-- <command...>]}"
shift 2

LOWEST="${LOWEST:-0}"
if [ "${1:-}" = "lowest" ]; then
    LOWEST=1
    shift
fi
if [ "${1:-}" = "--" ]; then
    shift
fi
if [ "$#" -eq 0 ]; then
    set -- vendor/bin/phpunit
fi

REPO_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"

docker run --rm \
    -e LINT="${LINT:-0}" \
    -e LOWEST="$LOWEST" \
    -e LARAVEL="$LARAVEL" \
    -v "$REPO_ROOT":/src:ro \
    "cimg/php:${PHP_VERSION}" \
    bash -c '
        set -euo pipefail
        mkdir -p ~/app
        tar -C /src --exclude=./.git --exclude=./vendor --exclude=./composer.lock --exclude=./build --exclude=./.superpowers -cf - . | tar -C ~/app -xf -
        cd ~/app
        tools/scripts/ci-install.sh "$LARAVEL"
        exec "$@"
    ' bash "$@"
