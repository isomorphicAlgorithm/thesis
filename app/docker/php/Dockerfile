# app/docker/php/Dockerfile
FROM php:8.3-fpm

# ┌─────────────────────────────────────────────────────────────┐
# │     IMAGE: php:8.3-fpm with Composer, Node/Yarn, Symfony   │
# └─────────────────────────────────────────────────────────────┘

# 1) Install system libs + PHP extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      unzip zip git curl build-essential \
      libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
      libjpeg-dev libfreetype6-dev default-mysql-client \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install intl zip pdo pdo_mysql mbstring opcache gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2) Node.js & Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y nodejs \
 && npm install --global yarn

# 3) Composer & Symfony CLI
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://get.symfony.com/cli/installer | bash \
 && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# 4) Make Composer cache writable
ENV COMPOSER_HOME=/tmp/composer
RUN mkdir -p $COMPOSER_HOME && chmod 777 $COMPOSER_HOME

# 5) Set working dir
ENV APP_DIR=/app
WORKDIR $APP_DIR

# 6) Copy full app code (needed for Symfony scripts)
COPY . .

# 7) Install PHP dependencies at build time
RUN composer install --prefer-dist --no-interaction --optimize-autoloader

# 8) Install JS dependencies at build time
RUN yarn install --frozen-lockfile
COPY . .

# 9) Entrypoint (outside code directory)
COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

