#!/bin/bash
set -e

echo "== MicroMarket: iniciando contenedor =="

# Logs visibles en el dashboard de Railway
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

# TEMPORAL: depuracion del error 500 - QUITAR despues de diagnosticar
export APP_DEBUG=true
export APP_ENV=local

# Arrancar el servidor de inmediato (en background) para que el
# healthcheck de Railway (/up) reciba 200 sin esperar migraciones
php artisan serve --host=0.0.0.0 --port="${PORT:-8080}" &
SERVER_PID=$!

# Esperar a que la base de datos este lista y ejecutar migraciones
attempt=1
until php artisan migrate --force; do
    if [ "$attempt" -ge 40 ]; then
        echo "ERROR: la base de datos no esta disponible tras 2 minutos."
        echo "Revisa que agregaste la variable DB_URL (o DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD) en Railway."
        kill "$SERVER_PID" 2>/dev/null || true
        exit 1
    fi
    echo "Base de datos no lista (intento $attempt/40), reintentando en 3s..."
    attempt=$((attempt+1))
    sleep 3
done

# Poblar la base de datos solo si esta vacia (seguro en redeploys)
if php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); exit((int) (\App\Models\User::count() > 0));'; then
    echo "== Poblando base de datos (primera vez) =="
    php artisan db:seed --force
else
    echo "== Base de datos ya contiene usuarios, se omite el seed =="
fi

# Link de storage (best-effort)
php artisan storage:link >/dev/null 2>&1 || true

echo "== MicroMarket listo en puerto ${PORT:-8080} =="
wait "$SERVER_PID"
