#!/bin/bash
set -e

echo "Starting MicroMarket..."

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Update .env with Railway database variables if available
if [ -n "$MYSQL_URL" ]; then
    echo "Configuring database from MYSQL_URL..."
    DB_HOST=$(echo $MYSQL_URL | sed -n 's|.*@\([^:]*\):\([0-9]*\)/.*|\1|p')
    DB_PORT=$(echo $MYSQL_URL | sed -n 's|.*@\([^:]*\):\([0-9]*\)/.*|\2|p')
    DB_DATABASE=$(echo $MYSQL_URL | sed -n 's|.*/\([^?]*\).*|\1|p')
    DB_USERNAME=$(echo $MYSQL_URL | sed -n 's|.*://\([^:]*\):.*|\1|p')
    DB_PASSWORD=$(echo $MYSQL_URL | sed -n 's|.*://[^:]*:\([^@]*\)@.*|\1|p')

    sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|g" .env
    sed -i "s|DB_PORT=.*|DB_PORT=$DB_PORT|g" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|g" .env
fi

if [ -n "$DB_HOST" ] && [ -z "$MYSQL_URL" ]; then
    sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|g" .env
    sed -i "s|DB_PORT=.*|DB_PORT=${DB_PORT:-3306}|g" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|g" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|g" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|g" .env
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|g" .env
fi

# Cache config
php artisan config:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed database (only on first run)
if [ ! -f ".seeded" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
    touch .seeded
fi

echo "MicroMarket started successfully!"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
