# Agentes y Skills del MicroMarket

## Descripción del proyecto
Aplicación web en **Laravel** para gestionar un MicroMarket con los siguientes módulos:
- **Ventas**: registro de ventas con descuento automático de stock.
- **Inventario**: CRUD de productos, control de stock, alerta de stock bajo, ajustes.
- **Clientes y Proveedores**: CRUD de ambos.
- **Recibos**: generación de recibo en PDF por cada venta.
- **Login encriptado y hasheado** (bcrypt) para trabajadores del MicroMarket.
- **Roles**: `admin` (control total) y `vendedor` (solo ventas / recibos). Permisos restringidos por middleware de rol.

## Agentes Disponibles

### 1. Document Generator
**Ubicación**: `.opencode/agents/document-generator.md`
**Skills**:
- `laravel-document-pdf` - Generación de PDF con DomPDF
- `laravel-document-word` - Generación de Word con PHPWord
- `laravel-document-templates` - Plantillas Blade profesionales

**Uso**: Cuando necesites generar recibos, reportes, contratos, cartas o certificados.

### 2. Database Builder
**Ubicación**: `.opencode/agents/database-builder.md`
**Skills**:
- `laravel-eloquent-models` - Modelos y relaciones
- `laravel-migrations` - Migraciones y esquemas
- `laravel-seeders-factories` - Datos de prueba
- `laravel-query-performance` - Optimización de consultas

**Uso**: Cuando necesites crear o modificar modelos, migraciones o consultas.

### 3. API Developer
**Ubicación**: `.opencode/agents/api-developer.md`
**Skills**:
- `laravel-routing` - Rutas y controladores
- `laravel-validation` - Form Requests
- `laravel-middleware` - Autenticación y roles
- `laravel-api-resources` - Recursos API

**Uso**: Cuando necesites crear endpoints, controladores o rutas.

### 4. Frontend Builder
**Ubicación**: `.opencode/agents/frontend-builder.md`
**Skills**:
- `laravel-blade-views` - Plantillas Blade
- `laravel-forms-validation` - Formularios
- `tailwind-css` - Estilos con Tailwind
- `laravel-components` - Componentes reutilizables

**Uso**: Cuando necesites crear vistas, formularios o estilos.

### 5. Docker Manager
**Ubicación**: `.opencode/agents/docker-manager.md`
**Skills**:
- `docker-containers` - Gestión de contenedores
- `docker-compose` - Servicios y orquestación
- `docker-troubleshooting` - Resolución de problemas
- `docker-optimization` - Optimización de imágenes

**Uso**: Cuando necesites gestionar el entorno Docker.

### 6. Test Writer
**Ubicación**: `.opencode/agents/test-writer.md`
**Skills**:
- `laravel-testing` - Patrones de testing
- `laravel-factories` - Factories y fakes
- `pest-patterns` - Sintaxis Pest
- `api-testing` - Tests de API

**Uso**: Cuando necesites escribir tests.

## Comandos de Ejecución

### Instalar Dependencias
```bash
# DomPDF para PDF
docker compose exec app composer require barryvdh/laravel-dompdf

# PHPWord para Word
docker compose exec app composer require phpoffice/phpword
```

### Crear Modelos
```bash
# Crear modelo con migración
docker compose exec app php artisan make:model Cliente -m
docker compose exec app php artisan make:model Producto -m
docker compose exec app php artisan make:model Venta -m
docker compose exec app php artisan make:model VentaDetalle -m
docker compose exec app php artisan make:model Categoria -m
docker compose exec app php artisan make:model Proveedor -m
```

### Crear Controladores
```bash
# Crear controlador resource
docker compose exec app php artisan make:controller VentaController --model=Venta
docker compose exec app php artisan make:controller ProductoController --model=Producto
docker compose exec app php artisan make:controller ClienteController --model=Cliente
docker compose exec app php artisan make:controller DocumentController
```

### Ejecutar Migraciones
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan migrate:fresh --seed
```

### Ejecutar Tests
```bash
docker compose exec app php artisan test
docker compose exec app php artisan test --filter=VentaTest
```

## Flujo de Trabajo Recomendado

1. **Primero**: Crear modelos y migraciones (Database Builder)
2. **Segundo**: Crear controladores y rutas (API Developer)
3. **Tercero**: Crear vistas y formularios (Frontend Builder)
4. **Cuarto**: Instalar dependencias de documentos (Document Generator)
5. **Quinto**: Crear servicios de documentos (Document Generator)
6. **Sexto**: Escribir tests (Test Writer)
7. **Último**: Gestionar Docker si es necesario (Docker Manager)

## Archivos del Proyecto

```
micromarket/
├── .opencode/
│   ├── agents/                    # Configuraciones de agentes
│   │   ├── document-generator.md
│   │   ├── database-builder.md
│   │   ├── api-developer.md
│   │   ├── frontend-builder.md
│   │   ├── docker-manager.md
│   │   └── test-writer.md
│   └── skills/                    # Skills instalados
│       ├── laravel-document-pdf/
│       ├── laravel-document-word/
│       ├── laravel-document-templates/
│       ├── laravel-eloquent-models/
│       ├── laravel-routing/
│       ├── docker-containers/
│       └── laravel-testing/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Models/
│   └── Services/
│       └── DocumentGeneration/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
│       └── documents/
├── routes/
├── tests/
├── docker-compose.yml
├── Dockerfile
└── AGENTS.md
```

## Notas Importantes

- **Agente Principal**: Document Generator es el agente más importante para este proyecto
- **Skills Reutilizables**: Los skills están diseñados para ser reutilizados en otros proyectos Laravel
- **Docker**: Todos los comandos deben ejecutarse con `docker compose exec app` porque PHP no está instalado nativamente
- **Testing**: Siempre escribir tests después de implementar funcionalidad
- **Documentación**: Mantener AGENTS.md actualizado con nuevos agentes o skills
