#!/bin/bash
set -e

# Optional: Wait for MySQL to be ready
if [ "$WAIT_FOR_DB" = "1" ]; then
  echo "Waiting for MySQL to be ready..."
  until mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 1
  done
fi

# Only install dependencies if not already present
if [ ! -f vendor/autoload.php ]; then
  echo "Installing PHP dependencies..."
  composer install --prefer-dist --no-interaction
fi

if [ ! -d node_modules ]; then
  echo "Installing JS dependencies..."
  yarn install
fi

# Build assets only in dev
if [ "$APP_ENV" = "dev" ]; then
  echo "Running Encore in dev mode..."
  yarn dev
else
  echo "Building Encore assets for production..."
  yarn build
fi

# Run migrations automatically if enabled
if [ "$RUN_MIGRATIONS" = "1" ] && [ -f bin/console ]; then
  echo "Running doctrine migrations..."
  php bin/console doctrine:migrations:migrate --no-interaction
fi

# Start PHP-FPM (default)
exec php-fpm
