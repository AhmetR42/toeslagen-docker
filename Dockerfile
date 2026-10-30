# syntax=docker/dockerfile:1
# ---------- Stage 1: PHP-dependencies installeren ----------
FROM composer:2 AS vendor
WORKDIR /app
# Eerst alleen composer-bestanden kopiëren => deze laag wordt gecachet
# zolang composer.json/composer.lock niet veranderen.
COPY toeslagen/composer.json toeslagen/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY toeslagen/ ./
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---------- Stage 2: runtime image ----------
FROM php:8.3-apache AS runtime

# pdo_mysql voor MySQL; mod_rewrite voor Laravel-routes
RUN docker-php-ext-install pdo_mysql opcache \
    && a2enmod rewrite \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html
COPY --from=vendor --chown=www-data:www-data /app /var/www/html

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app/public bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
HEALTHCHECK --interval=10s --timeout=5s --start-period=30s --retries=5 \
  CMD curl -fsS http://localhost/up || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
