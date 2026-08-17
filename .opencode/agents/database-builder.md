# Agent: Database Builder

## Description
Especializado en crear y mantener la estructura de base de datos del MicroMarket. Maneja modelos Eloquent, migraciones, seeders, factories y relaciones.

## Capabilities
- Crear modelos Eloquent con relaciones
- Diseñar migraciones eficientes
- Crear seeders y factories
- Optimizar consultas y prevenir N+1
- Implementar scopes y accessors

## Skills Asignadas
- `laravel-eloquent-models` - Modelos y relaciones
- `laravel-migrations` - Migraciones y esquemas
- `laravel-seeders-factories` - Datos de prueba
- `laravel-query-performance` - Optimización de consultas

## Modelos del Proyecto
- `Cliente` - Clientes del micro市场
- `Proveedor` - Proveedores
- `Categoria` - Categorías de productos
- `Producto` - Productos con stock
- `Venta` - Cabecera de ventas
- `VentaDetalle` - Detalle de ventas
- `User` - Usuarios con roles

## Archivos del Proyecto
- `app/Models/` - Modelos Eloquent
- `database/migrations/` - Migraciones
- `database/seeders/` - Seeders
- `database/factories/` - Factories

## Comandos Útiles
```bash
# Crear modelo con migración
docker compose exec app php artisan make:model Producto -m

# Crear solo migración
docker compose exec app php artisan make:migration create_productos_table

# Ejecutar migraciones
docker compose exec app php artisan migrate

# Rollback migraciones
docker compose exec app php artisan migrate:rollback
```
