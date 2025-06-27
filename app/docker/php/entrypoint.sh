#!/bin/bash
set -e

# ——————————————————————————————————————————————————————————
# FIX UPLOADS PERMISSIONS
# ——————————————————————————————————————————————————————————
echo "→ Fixing upload folder permissions…"
chown -R www-data:www-data public/uploads
chmod -R 0755 public/uploads

# 1) Wait for MySQL
if [ "$WAIT_FOR_DB" = "1" ]; then
  echo "Waiting for MySQL at $DB_HOST…"
  until mysqladmin ping -h"$DB_HOST" --silent; do sleep 1; done
fi

# 1.5) Fix perms on vendor/ so Composer (running as non-root) can delete/install
echo "Fixing permissions on /app/vendor …"
chown -R "$(id -u):$(id -g)" /app/vendor || true

# 2) Always install PHP deps (ensures a clean, up-to-date vendor/)
echo "→ (Re)Installing PHP dependencies…"
composer install --prefer-dist --no-interaction

# 3) JS deps
if [ ! -d node_modules ]; then
  echo "→ Installing JS dependencies…"
  yarn install
fi

# 4) Build assets
if [ "$APP_ENV" = "dev" ]; then
  echo "→ Running Encore in dev mode…"
  yarn dev
else
  echo "→ Building Encore for production…"
  yarn build
fi

# 5) Migrations
if [ "$RUN_MIGRATIONS" = "1" ] && [ -f bin/console ]; then
  echo "→ Running doctrine migrations…"
  php bin/console doctrine:migrations:migrate --no-interaction
fi

# 6) Start PHP-FPM
exec php-fpm
