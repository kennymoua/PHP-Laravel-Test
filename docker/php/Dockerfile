FROM php:8.3-cli

# System deps
RUN apt-get update && apt-get install -y \
  git unzip rsync libzip-dev libsqlite3-dev \
  && docker-php-ext-install pdo pdo_sqlite zip \
  && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Friendly defaults
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini

# Entry point
COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
