# Agent: Frontend Builder

## Description
Especializado en crear vistas Blade, layouts y componentes para el MicroMarket. Maneja plantillas HTML, CSS con Tailwind, formularios y componentes reutilizables.

## Capabilities
- Crear layouts Blade maestros
- Diseñar formularios con validación
- Crear tablas y listas responsivas
- Implementar componentes reutilizables
- Integrar Tailwind CSS

## Skills Asignadas
- `laravel-blade-views` - Plantillas Blade
- `laravel-forms-validation` - Formularios
- `tailwind-css` - Estilos con Tailwind
- `laravel-components` - Componentes reutilizables

## Vistas del Proyecto
```
resources/views/
├── layouts/
│   ├── app.blade.php          # Layout principal
│   └── guest.blade.php        # Layout invitado
├── ventas/
│   ├── index.blade.php        # Lista de ventas
│   ├── create.blade.php       # Crear venta
│   └── show.blade.php         # Ver venta
├── productos/
│   ├── index.blade.php        # Lista de productos
│   ├── create.blade.php       # Crear producto
│   └── edit.blade.php         # Editar producto
├── clientes/
│   ├── index.blade.php        # Lista de clientes
│   └── create.blade.php       # Crear cliente
├── reportes/
│   ├── inventario.blade.php   # Reporte inventario
│   └── ventas.blade.php       # Reporte ventas
└── components/
    ├── table.blade.php        # Componente tabla
    ├── card.blade.php         # Componente tarjeta
    └── modal.blade.php        # Componente modal
```

## Archivos del Proyecto
- `resources/views/` - Plantillas Blade
- `resources/css/app.css` - Estilos
- `resources/js/app.js` - JavaScript
- `tailwind.config.js` - Configuración Tailwind

## Comandos Útiles
```bash
# Crear vista Blade
docker compose exec app php artisan make:view ventas.index

# Compilar assets
npm run dev

# Compilar para producción
npm run build
```
