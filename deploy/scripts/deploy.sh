#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/artisan-seo/current}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"

cd "$APP_DIR"

echo "==> Pull latest code"
git fetch origin
git checkout main
git pull --ff-only origin main

echo "==> Install PHP dependencies"
"$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader

if command -v "$NPM_BIN" >/dev/null 2>&1; then
  echo "==> Build frontend assets"
  "$NPM_BIN" ci
  "$NPM_BIN" run build
fi

echo "==> Laravel optimization"
"$PHP_BIN" artisan storage:link || true
"$PHP_BIN" artisan migrate --force
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan queue:restart || true
"$PHP_BIN" artisan horizon:terminate || true

echo "==> Deployment finished"
