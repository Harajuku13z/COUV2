#!/usr/bin/env bash
set -euo pipefail

if [ "$#" -lt 2 ]; then
  echo "Usage: $0 <tenant-id> <domain> [database]"
  exit 1
fi

TENANT_ID="$1"
DOMAIN="$2"
DATABASE_NAME="${3:-tenant_${TENANT_ID}}"
PHP_BIN="${PHP_BIN:-php}"
APP_DIR="${APP_DIR:-/var/www/artisan-seo/current}"

cd "$APP_DIR"

echo "==> Creating tenant ${TENANT_ID} for ${DOMAIN}"
"$PHP_BIN" artisan tinker --execute="\
\$tenant = \App\Models\Tenant::query()->firstOrCreate(['id' => '${TENANT_ID}']); \
\$tenant->domains()->firstOrCreate(['domain' => '${DOMAIN}']); \
\$tenant->put('database', '${DATABASE_NAME}');"

echo "==> Running tenant migrations"
"$PHP_BIN" artisan tenants:migrate --tenants="${TENANT_ID}" --force

echo "==> Tenant ready"
