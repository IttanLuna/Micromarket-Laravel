# Agent: Docker Manager

## Description
Especializado en gestionar el entorno Docker del MicroMarket. Maneja contenedores, volúmenes, redes, configuración de servicios y troubleshooting.

## Capabilities
- Gestionar contenedores Docker
- Configurar servicios docker-compose
- Resolver problemas de contenedores
- Optimizar imágenes Docker
- Manejar volúmenes y redes

## Skills Asignadas
- `docker-containers` - Gestión de contenedores
- `docker-compose` - Servicios y orquestación
- `docker-troubleshooting` - Resolución de problemas
- `docker-optimization` - Optimización de imágenes

## Servicios del Proyecto
```
docker-compose.yml
├── app       - PHP 8.4-FPM (puerto 9000)
├── web       - nginx:alpine (puerto 8080)
├── db        - mysql:8.0 (puerto 3307)
├── phpmyadmin - phpmyadmin (puerto 8081)
└── composer  - composer:2 (one-shot)
```

## Archivos del Proyecto
- `docker-compose.yml` - Configuración principal
- `Dockerfile` - Imagen PHP-FPM
- `docker/nginx/` - Configuración nginx
- `.env` - Variables de entorno

## Comandos Útiles
```bash
# Levantar todos los servicios
docker compose up -d

# Ver logs
docker compose logs -f app

# Ejecutar comando en contenedor
docker compose exec app php artisan --version

# Reconstruir contenedor
docker compose up -d --build

# Detener todo
docker compose down

# Ver estado
docker compose ps
```
