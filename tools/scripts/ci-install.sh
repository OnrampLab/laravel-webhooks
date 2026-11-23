#!/usr/bin/env bash
# Install dependencies for one Laravel version lane.
#
# Usage: tools/scripts/ci-install.sh <illuminate/support constraint>
#   LINT=1    keep lint-only dev dependencies (larastan, rector, phpinsights, ...)
#   LOWEST=1  resolve with --prefer-lowest
#
# WARNING: modifies composer.json in place. Run it only in CI or in a
# throwaway copy of the repository (see tools/scripts/test-matrix.sh).
set -euo pipefail

LARAVEL="${1:?usage: ci-install.sh <illuminate/support constraint>}"

if [ "${LINT:-0}" != "1" ]; then
    # Dev dependencies only needed for lint; they have higher PHP floors
    # than the oldest lanes. phpunit, testbench and mockery stay.
    composer remove --dev --no-update --no-interaction \
        nunomaduro/larastan \
        nunomaduro/phpinsights \
        rector/rector \
        spatie/phpunit-watcher \
        phpmd/phpmd \
        onramplab/onr-phpcs-laravel \
        squizlabs/php_codesniffer
fi

# Every laravel/framework release of 8.x-11.x is flagged by security
# advisories, so Composer 2.9+ refuses to install them. Allow them only in
# these EOL lanes; 12.x and 13.x keep advisory blocking on.
case "$LARAVEL" in
    ^8.*|^9.*|^10.*|^11.*)
        # Composer < 2.9 has no such setting and does not block advisories.
        if ! composer config --no-interaction audit.block-insecure false 2>/dev/null; then
            echo "Composer $(composer --version --no-ansi | awk '{print $3}') has no audit.block-insecure; nothing to relax."
        fi
        ;;
esac

composer require --no-update --no-interaction "illuminate/support:${LARAVEL}"

UPDATE_FLAGS=(--prefer-dist --no-interaction --no-progress)
if [ "${LOWEST:-0}" = "1" ]; then
    UPDATE_FLAGS+=(--prefer-lowest --prefer-stable)
fi

composer update "${UPDATE_FLAGS[@]}"
