FROM php:8.3-fpm

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip zip git curl build-essential \
    libicu-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install intl zip pdo pdo_mysql mbstring opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (LTS) and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install --global yarn

# Set working directory
ENV APP_DIR=/app
WORKDIR $APP_DIR

# Entrypoint
COPY php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
