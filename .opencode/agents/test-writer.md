# Agent: Test Writer

## Description
Especializado en escribir tests para el MicroMarket. Maneja tests unitarios, tests de feature, tests de API y tests de modelos con Pest o PHPUnit.

## Capabilities
- Escribir tests unitarios
- Crear tests de feature
- Testear endpoints API
- Testear modelos y relaciones
- Usar factories y fakes

## Skills Asignadas
- `laravel-testing` - Patrones de testing
- `laravel-factories` - Factories y fakes
- `pest-patterns` - Sintaxis Pest
- `api-testing` - Tests de API

## Estructura de Tests
```
tests/
├── Unit/
│   ├── Models/
│   │   ├── ClienteTest.php
│   │   ├── ProductoTest.php
│   │   └── VentaTest.php
│   └── Services/
│       └── DocumentServiceTest.php
├── Feature/
│   ├── Api/
│   │   ├── VentaTest.php
│   │   ├── ProductoTest.php
│   │   └── DocumentTest.php
│   └── Auth/
│       └── LoginTest.php
└── TestCase.php
```

## Archivos del Proyecto
- `tests/` - Directorio de tests
- `phpunit.xml` - Configuración PHPUnit
- `database/factories/` - Factories para tests

## Comandos Útiles
```bash
# Ejecutar todos los tests
docker compose exec app php artisan test

# Ejecutar un test específico
docker compose exec app php artisan test --filter=VentaTest

# Ejecutar con Pest
docker compose exec app php artisan pest

# Ejecutar tests unitarios
docker compose exec app php artisan test --testsuite=Unit

# Ejecutar tests de feature
docker compose exec app php artisan test --testsuite=Feature
```
