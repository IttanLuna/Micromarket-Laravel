# MicroMarket - Laravel + Docker

Sistema de control de ventas, inventario, clientes, proveedores, recibos y login con roles (admin/vendedor).

## Stack
- PHP 8.3-FPM (app) + Nginx -> http://localhost:8080
- MySQL 8.0 -> localhost:3307
- phpMyAdmin -> http://localhost:8081

## Primer arranque (tras reiniciar con VT-x activo)
```powershell
# 1. WSL2 (ejecutar como Administrador)
wsl --install
# reiniciar

# 2. Instalar Docker Desktop
#    C:\Users\LUNA\AppData\Local\Temp\opencode\docker\DockerDesktopInstaller.exe

# 3. Crear el proyecto Laravel (solo la primera vez)
docker compose run --rm composer

# 4. Copiar y configurar .env de Laravel (DB_HOST=db, DB_PORT=3306, DB_DATABASE/USERNAME/PASSWORD segun .env raiz)
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

# 5. Levantar todo
docker compose up -d

# 6. Migraciones + usuarios admin/vendedor
docker compose exec app php artisan migrate --seed
```

## Uso diario
- Iniciar: `docker compose up -d`
- Detener: `docker compose down`
- Comandos artisan: `docker compose exec app php artisan ...`
- Logs: `docker compose logs -f app web`
