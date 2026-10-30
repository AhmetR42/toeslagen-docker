#!/bin/sh
set -e
cd /var/www/html

# APP_KEY: als die niet in .env staat, één keer genereren en bewaren in het storage-volume
if [ -z "$APP_KEY" ]; then
  KEYFILE=storage/app/.app_key
  if [ ! -f "$KEYFILE" ]; then
    php -r 'echo "base64:".base64_encode(random_bytes(32));' > "$KEYFILE"
    echo "[entrypoint] Nieuwe APP_KEY gegenereerd (opgeslagen in storage-volume)"
  fi
  export APP_KEY="$(cat "$KEYFILE")"
fi

# Volume kan als root zijn aangemaakt -> rechten herstellen
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app/public
chown -R www-data:www-data storage bootstrap/cache

php artisan package:discover --ansi >/dev/null

# Wachten tot de database bereikbaar is (extra zekerheid naast de healthcheck)
i=0
until php -r 'try{new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT"),getenv("DB_USERNAME"),getenv("DB_PASSWORD"));}catch(Exception $e){exit(1);}'; do
  i=$((i+1)); [ $i -ge 30 ] && echo "[entrypoint] Database niet bereikbaar" && exit 1
  echo "[entrypoint] Wachten op database... ($i)"; sleep 2
done

# Database is al gevuld via docker/mysql/init/*.sql; migrate draait alleen nieuwe migraties
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
