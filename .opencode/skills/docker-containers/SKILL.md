---
name: docker-containers
description: "Gestionar contenedores Docker para el MicroMarket. docker-compose, resolución de problemas, optimización y configuración de servicios."
---

# Docker Containers Skill

Gestiona el entorno Docker del MicroMarket.

## Arquitectura Docker

```
micromarket/
├── docker-compose.yml      # Orquestación de servicios
├── Dockerfile              # Imagen PHP-FPM
├── docker/
│   └── nginx/
│       └── default.conf    # Configuración nginx
└── .env                    # Variables de entorno
```

## Servicios

| Servicio | Imagen | Puerto | Función |
|----------|--------|--------|---------|
| app | php:8.4-fpm | 9000 | Backend PHP |
| web | nginx:alpine | 8080 | Servidor web |
| db | mysql:8.0 | 3307 | Base de datos |
| phpmyadmin | phpmyadmin | 8081 | Admin BD |
| composer | composer:2 | - | Dependencias PHP |

## Comandos Básicos

```bash
# Levantar todos los servicios
docker compose up -d

# Detener todos los servicios
docker compose down

# Ver estado
docker compose ps

# Ver logs
docker compose logs -f

# Ver logs de un servicio
docker compose logs -f app
```

## Ejecutar Comandos en Contenedores

```bash
# Ejecutar comando en app
docker compose exec app php artisan --version

# Ejecutar composer
docker compose exec app composer install

# Ejecutar migraciones
docker compose exec app php artisan migrate

# Ejecutar tinker
docker compose exec app php artisan tinker

# Ejecutar con usuario root
docker compose exec -u root app bash
```

## Reconstruir Contenedores

```bash
# Reconstruir después de cambiar Dockerfile
docker compose up -d --build

# Reconstruir un servicio específico
docker compose up -d --build app

# Forzar reconstrucción sin caché
docker compose build --no-cache app
```

## Dockerfile

```dockerfile
FROM php:8.4-fpm

# Instalar extensiones
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif bcmath gd zip pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar working directory
WORKDIR /var/www

# Copiar archivos
COPY . /var/www

# Permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Exponer puerto
EXPOSE 9000

CMD ["php-fpm"]
```

## docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: micromarket-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./src:/var/www
    networks:
      - micromarket-network
    depends_on:
      - db

  web:
    image: nginx:alpine
    container_name: micromarket-web
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - micromarket-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: micromarket-db
    restart: unless-stopped
    ports:
      - "3307:3306"
    environment:
      MYSQL_DATABASE: micromarket
      MYSQL_USER: micromarket
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - micromarket-db-data:/var/lib/mysql
    networks:
      - micromarket-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: micromarket-pma
    restart: unless-stopped
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
    networks:
      - micromarket-network

  composer:
    image: composer:2
    container_name: micromarket-composer
    working_dir: /var/www
    volumes:
      - ./src:/var/www
    networks:
      - micromarket-network
    command: sh -c "composer install --no-interaction --optimize-autoloader"

networks:
  micromarket-network:
    driver: bridge

volumes:
  micromarket-db-data:
```

## Resolución de Problemas

### Contenedor no inicia

```bash
# Ver logs del contenedor
docker compose logs app

# Verificar errores de sintaxis
docker compose config

# Reconstruir
docker compose up -d --build
```

### Error de permisos

```bash
# Cambiar permisos
docker compose exec -u root app chown -R www-data:www-data /var/www

# O en Windows
docker compose exec -u root app chmod -R 755 /var/www/storage
```

### No conecta a base de datos

```bash
# Verificar que db esté corriendo
docker compose ps db

# Verificar configuración
docker compose exec app cat .env | grep DB_

# Probar conexión
docker compose exec db mysql -u micromarket -psecret micromarket
```

### Puerto en uso

```bash
# Ver qué usa el puerto
netstat -ano | findstr :8080

# Cambiar puerto en docker-compose.yml
ports:
  - "8081:80"  # Cambiar 8080 por 8081
```

### Memoria insuficiente

```bash
# Ver uso de recursos
docker stats

# Limitar memoria en docker-compose.yml
services:
  app:
    mem_limit: 512m
```

## Optimización

1. **Usar multi-stage builds** para reducir tamaño de imagen
2. **Aprovechar caché de Docker** ordenando bien las capas
3. **Usar .dockerignore** para excluir archivos innecesarios
4. **Volumes nombrados** para persistir datos
5. **Redes bridge** para aislamiento de servicios
