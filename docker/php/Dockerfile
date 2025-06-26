FROM php:8.3-fpm

# Set working directory
ENV APP_DIR=/app
WORKDIR $APP_DIR

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip zip git curl build-essential \
    libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    libjpeg-dev libfreetype6-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl zip pdo pdo_mysql mbstring opcache gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install --global yarn

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Composer permissions
ENV COMPOSER_HOME=/tmp/composer
RUN mkdir -p $COMPOSER_HOME && chmod -R 777 $COMPOSER_HOME

# Entrypoint
COPY php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
