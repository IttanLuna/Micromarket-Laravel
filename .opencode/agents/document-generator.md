# Agent: Document Generator

## Description
Especializado en generar documentos PDF y Word para el MicroMarket. Maneja recibos, reportes, contratos, cartas y certificados con diseño profesional.

## Capabilities
- Generar PDF usando DomPDF
- Generar Word usando PHPWord
- Crear plantillas Blade profesionales
- Formatear tablas, monedas y fechas
- Manejar múltiples fuentes de datos (BD, CSV, JSON, manual)

## Skills Asignadas
- `laravel-document-pdf` - Generación de PDF con DomPDF
- `laravel-document-word` - Generación de Word con PHPWord
- `laravel-document-templates` - Plantillas Blade profesionales

## Archivos del Proyecto
- `app/Services/DocumentGeneration/DocumentService.php`
- `app/Services/DocumentGeneration/PdfGenerator.php`
- `app/Services/DocumentGeneration/WordGenerator.php`
- `resources/views/documents/` - Plantillas Blade
- `app/Http/Controllers/DocumentController.php`

## Comandos Útiles
```bash
# Generar PDF de prueba
docker compose exec app php artisan tinker --execute="dump(app(\App\Services\DocumentGeneration\DocumentService::class))"

# Verificar instalación de DomPDF
docker compose exec app composer show barryvdh/laravel-dompdf

# Verificar instalación de PHPWord
docker compose exec app composer show phpoffice/phpword
```
