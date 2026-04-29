#!/usr/bin/env bash
set -euo pipefail

TARGET_FILE="${1:-.env}"
SOURCE_TEMPLATE="deploy/.env.production.example"

if [ ! -f "$SOURCE_TEMPLATE" ]; then
  echo "Template introuvable: $SOURCE_TEMPLATE"
  exit 1
fi

if [ -f "$TARGET_FILE" ]; then
  printf "Le fichier %s existe deja. Le remplacer ? [y/N] " "$TARGET_FILE"
  read -r overwrite
  if [[ ! "$overwrite" =~ ^[Yy]$ ]]; then
    echo "Operation annulee."
    exit 0
  fi
fi

cp "$SOURCE_TEMPLATE" "$TARGET_FILE"

prompt() {
  local key="$1"
  local label="$2"
  local default_value="$3"
  local value

  printf "%s [%s]: " "$label" "$default_value"
  read -r value
  value="${value:-$default_value}"

  python3 - "$TARGET_FILE" "$key" "$value" <<'PY'
from pathlib import Path
import re
import sys

target = Path(sys.argv[1])
key = sys.argv[2]
value = sys.argv[3]

text = target.read_text()
quoted = '"' in value or " " in value or value.startswith("http")
replacement = f'{key}="{value}"' if quoted else f"{key}={value}"
pattern = re.compile(rf"^{re.escape(key)}=.*$", re.MULTILINE)
text = pattern.sub(replacement, text)
target.write_text(text)
PY
}

echo "Configuration du fichier $TARGET_FILE"

prompt "APP_NAME" "Nom de l application" "Artisan SEO Platform"
prompt "APP_URL" "URL principale" "https://lj.osmoseconsulting.fr"
prompt "APP_DOMAIN" "Domaine principal" "lj.osmoseconsulting.fr"
prompt "APP_ADMIN_DOMAIN" "Domaine admin" "admin.lj.osmoseconsulting.fr"

prompt "DB_HOST" "Hote MySQL central" "127.0.0.1"
prompt "DB_PORT" "Port MySQL central" "3306"
prompt "DB_DATABASE" "Base centrale" "artisan_seo_central"
prompt "DB_USERNAME" "Utilisateur MySQL central" "artisan_user"
prompt "DB_PASSWORD" "Mot de passe MySQL central" "change-me"

prompt "TENANCY_DB_HOST" "Hote MySQL tenant" "127.0.0.1"
prompt "TENANCY_DB_PORT" "Port MySQL tenant" "3306"
prompt "TENANCY_DB_USERNAME" "Utilisateur MySQL tenant" "artisan_user"
prompt "TENANCY_DB_PASSWORD" "Mot de passe MySQL tenant" "change-me"

prompt "MAIL_MAILER" "Mailer" "smtp"
prompt "MAIL_HOST" "Hote SMTP" "smtp.hostinger.com"
prompt "MAIL_PORT" "Port SMTP" "465"
prompt "MAIL_USERNAME" "Utilisateur SMTP" "no-reply@lj.osmoseconsulting.fr"
prompt "MAIL_PASSWORD" "Mot de passe SMTP" "change-me"
prompt "MAIL_FROM_ADDRESS" "Email expediteur" "no-reply@lj.osmoseconsulting.fr"

prompt "QUEUE_CONNECTION" "Queue connection" "database"
prompt "CACHE_STORE" "Cache store" "file"
prompt "SESSION_DRIVER" "Session driver" "file"
prompt "TENANCY_CENTRAL_DOMAINS" "Domaines centraux (separes par virgules)" "127.0.0.1,localhost,lj.osmoseconsulting.fr,www.lj.osmoseconsulting.fr,admin.lj.osmoseconsulting.fr"

echo "Fichier $TARGET_FILE genere."
echo "Pense a verifier les cles API et APP_KEY avant mise en prod."
