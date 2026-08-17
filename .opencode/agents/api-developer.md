# Agent: API Developer

## Description
Especializado en crear endpoints API y controladores para el MicroMarket. Maneja rutas, controladores, middleware, form requests y recursos API.

## Capabilities
- Crear controladores RESTful
- Definir rutas y grupos de rutas
- Implementar middleware de autenticación y roles
- Crear Form Requests para validación
- Crear recursos API para respuestas formateadas

## Skills Asignadas
- `laravel-routing` - Rutas y controladores
- `laravel-validation` - Form Requests
- `laravel-middleware` - Autenticación y roles
- `laravel-api-resources` - Recursos API

## Endpoints del Proyecto
```
# Ventas
GET    /api/ventas          - Listar ventas
POST   /api/ventas          - Crear venta
GET    /api/ventas/{id}     - Ver venta
PUT    /api/ventas/{id}     - Actualizar venta

# Productos
GET    /api/productos       - Listar productos
POST   /api/productos       - Crear producto
GET    /api/productos/{id}  - Ver producto
PUT    /api/productos/{id}  - Actualizar producto

# Clientes
GET    /api/clientes        - Listar clientes
POST   /api/clientes        - Crear cliente

# Documentos
POST   /api/documents/generate    - Generar documento
GET    /api/documents/download/{hash} - Descargar documento
```

## Archivos del Proyecto
- `app/Http/Controllers/Api/` - Controladores API
- `app/Http/Requests/` - Form Requests
- `app/Http/Middleware/` - Middleware
- `app/Http/Resources/` - Recursos API
- `routes/api.php` - Rutas API

## Comandos Útiles
```bash
# Crear controlador API
docker compose exec app php artisan make:controller Api/VentaController --api --model=Venta

# Crear Form Request
docker compose exec app php artisan make:request StoreVentaRequest

# Crear recurso API
docker compose exec app php artisan make:resource VentaResource
```
