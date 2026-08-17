---
name: laravel-document-word
description: "Generación de documentos Word (.docx) en Laravel usando phpoffice/phpword. Crear certificados, contratos, cartas y reportes con formato profesional."
---

# Laravel Document Word Skill

Genera documentos Word (.docx) profesionales usando PHPWord en Laravel.

## Instalación

```bash
docker compose exec app composer require phpoffice/phpword
```

## Uso Básico

### Generar Documento Word Simple

```php
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;

$phpWord = new \PhpOffice\PhpWord\Document();

// Agregar sección
$section = $phpWord->addSection();

// Agregar título
$section->addTitle('Certificado de Trabajo', 1);

// Agregar párrafo
$section->addParagraph('La empresa MICROMARKET S.A.C. certifica que:');

// Guardar
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('certificado.docx');
```

### Generar con Tabla

```php
$section = $phpWord->addSection();

// Agregar tabla
$table = $section->addTable();
$table->addRow();
$table->addCell('Producto')->addText('Arroz 5kg');
$table->addCell('Cantidad')->addText('2');
$table->addCell('Precio')->addText('S/. 12.50');
```

## Plantilla Word para Certificados

```php
<?php

namespace App\Services\DocumentGeneration;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Document;

class WordGenerator
{
    public function generarCertificado(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Estilo del título
        $titleStyle = [
            'align' => 'center',
            'font' => 'Arial',
            'size' => 16,
            'bold' => true,
        ];

        // Estilo del cuerpo
        $bodyStyle = [
            'font' => 'Arial',
            'size' => 12,
        ];

        // Título
        $section->addParagraph('CERTIFICADO DE TRABAJO', $titleStyle);

        // Espacio
        $section->addParagraph('');

        // Cuerpo del certificado
        $texto = "La empresa {$datos['empresa']} certifica que:";
        $section->addParagraph($texto, $bodyStyle);

        $section->addParagraph('');

        $empleado = "Sr(a). {$datos['empleado']}";
        $section->addParagraph($empleado, [
            'font' => 'Arial',
            'size' => 14,
            'bold' => true,
            'align' => 'center',
        ]);

        $section->addParagraph('');

        $cargo = "ha laborado en nuestra empresa como {$datos['cargo']}.";
        $section->addParagraph($cargo, $bodyStyle);

        // Firmas
        $section->addParagraph('');
        $section->addParagraph('');

        $table = $section->addTable();
        $table->addRow();
        $table->addCell('______________________');
        $table->addCell('______________________');
        $table->addRow();
        $table->addCell('Gerente General');
        $table->addCell($datos['empleado']);

        return $phpWord;
    }

    public function guardar(Document $phpWord, string $filename): void
    {
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save(storage_path("app/{$filename}"));
    }
}
```

## Estilos de Texto

```php
// Negrita
$section->addParagraph('Texto negrita', ['bold' => true]);

// Cursiva
$section->addParagraph('Texto cursiva', ['italic' => true]);

// Subrayado
$section->addParagraph('Texto subrayado', ['underline' => true]);

// Tamaño de fuente
$section->addParagraph('Texto grande', ['size' => 16]);

// Color
$section->addParagraph('Texto rojo', ['color' => 'FF0000']);

// Alineación
$section->addParagraph('Centrado', ['align' => 'center']);
$section->addParagraph('Derecha', ['align' => 'right']);
```

## Tablas

```php
// Crear tabla con estilo
$table = $section->addTable([
    'borderSize' => 1,
    'borderColor' => '000000',
    'cellMargin' => 50,
]);

// Agregar fila con encabezados
$table->addRow();
$cell = $table->addCell('Encabezado 1');
$cell->setStyle([
    'bold' => true,
    'bgColor' => 'CCCCCC',
]);

// Agregar fila de datos
$table->addRow();
$table->addCell('Dato 1');
$table->addCell('Dato 2');
```

## Guardar y Descargar

```php
// Guardar en disco
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save(storage_path('app/documento.docx'));

// Descargar directamente
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="documento.docx"');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit;
```

## Ejemplo Completo: Contrato

```php
<?php

namespace App\Services\DocumentGeneration;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Document;

class WordGenerator
{
    public function generarContrato(array $datos): Document
    {
        $phpWord = new Document();
        $section = $phpWord->addSection();

        // Título
        $section->addTitle('CONTRATO DE PROVEEDOR', 1);

        // Fecha
        $section->addParagraph("Fecha: {$datos['fecha']}");

        // Partes
        $section->addTitle('PARTES', 2);
        $section->addParagraph("EL PROVEEDOR: {$datos['proveedor']['nombre']}");
        $section->addParagraph("RUC: {$datos['proveedor']['ruc']}");

        // Cláusulas
        $section->addTitle('CLÁUSULAS', 2);
        
        foreach ($datos['clausulas'] as $index => $clausula) {
            $section->addTitle("Cláusula {$index + 1}: {$clausula['titulo']}", 3);
            $section->addParagraph($clausula['texto']);
        }

        // Firmas
        $section->addParagraph('');
        $section->addParagraph('');
        
        $table = $section->addTable();
        $table->addRow();
        $table->addCell('______________________');
        $table->addCell('______________________');
        $table->addRow();
        $table->addCell('EL PROVEEDOR');
        $table->addCell('MICROMARKET S.A.C.');

        return $phpWord;
    }
}
```

## Errores Comunes

| Error | Solución |
|-------|----------|
| Class not found | Verificar instalación con `composer show phpoffice/phpword` |
| Memory limit | Aumentar `memory_limit` en php.ini |
| Invalid HTML | PHPWord no soporta todo el HTML, usar API nativa |
| Fonts | Usar fuentes del sistema o incluir en documento |
