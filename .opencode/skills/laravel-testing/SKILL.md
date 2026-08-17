---
name: laravel-testing
description: "Escribir tests en Laravel para el MicroMarket. Tests unitarios, de feature, de API, modelos, controladores y servicios con Pest o PHPUnit."
---

# Laravel Testing Skill

Escribe tests completos para el MicroMarket.

## Configuración

```php
// phpunit.xml
<phpunit>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```

## Tests de Modelo

```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_producto_tiene_relacion_con_categoria(): void
    {
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->create([
            'categoria_id' => $categoria->id,
        ]);

        $this->assertInstanceOf(Categoria::class, $producto->categoria);
    }

    public function test_producto_reduce_stock(): void
    {
        $producto = Producto::factory()->create(['stock' => 10]);

        $resultado = $producto->reducirStock(3);

        $this->assertTrue($resultado);
        $this->assertEquals(7, $producto->fresh()->stock);
    }

    public function test_producto_no_reduce_stock_insuficiente(): void
    {
        $producto = Producto::factory()->create(['stock' => 2]);

        $resultado = $producto->reducirStock(5);

        $this->assertFalse($resultado);
        $this->assertEquals(2, $producto->fresh()->stock);
    }

    public function test_producto_calcula_margen(): void
    {
        $producto = Producto::factory()->create([
            'precio_compra' => 10.00,
            'precio_venta' => 15.00,
        ]);

        $this->assertEquals(50.0, $producto->margen);
    }

    public function test_scope_con_stock(): void
    {
        Producto::factory()->create(['stock' => 10]);
        Producto::factory()->create(['stock' => 0]);

        $productos = Producto::conStock()->get();

        $this->assertCount(1, $productos);
    }

    public function test_scope_stock_bajo(): void
    {
        Producto::factory()->create(['stock' => 5, 'stock_minimo' => 10]);
        Producto::factory()->create(['stock' => 20, 'stock_minimo' => 10]);

        $productos = Producto::stockBajo()->get();

        $this->assertCount(1, $productos);
    }
}
```

## Tests de Controlador

```php
<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\{
    User,
    Venta,
    Cliente,
    Producto,
    VentaDetalle,
};
use Illuminate\Foundation\Testing\RefreshDatabase;

class VentaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'vendedor']);
    }

    public function test_index_requiere_autenticacion(): void
    {
        $this->get(route('ventas.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_muestra_ventas(): void
    {
        $ventas = Venta::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('ventas.index'))
            ->assertOk()
            ->assertSee($ventas->first()->numero);
    }

    public function test_store_crea_venta(): void
    {
        $cliente = Cliente::factory()->create();
        $producto = Producto::factory()->create([
            'stock' => 10,
            'precio_venta' => 10.00,
        ]);

        $data = [
            'cliente_id' => $cliente->id,
            'metodo_pago' => 'efectivo',
            'monto_entregado' => 100.00,
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 2,
                    'precio' => 10.00,
                ],
            ],
        ];

        $this->actingAs($this->user)
            ->post(route('ventas.store'), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('ventas', [
            'user_id' => $this->user->id,
            'cliente_id' => $cliente->id,
        ]);

        $this->assertEquals(8, $producto->fresh()->stock);
    }

    public function test_store_valida_productos(): void
    {
        $data = [
            'cliente_id' => 999,
            'metodo_pago' => 'efectivo',
            'productos' => [],
        ];

        $this->actingAs($this->user)
            ->post(route('ventas.store'), $data)
            ->assertSessionHasErrors(['cliente_id', 'productos']);
    }
}
```

## Tests de API

```php
<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\{
    User,
    Venta,
    Producto,
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class VentaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_ventas_requiere_auth(): void
    {
        $this->getJson('/api/ventas')
            ->assertUnauthorized();
    }

    public function test_listar_ventas(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $ventas = Venta::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $this->getJson('/api/ventas')
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_crear_venta(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $producto = Producto::factory()->create([
            'stock' => 10,
            'precio_venta' => 25.00,
        ]);

        $data = [
            'cliente_id' => 1,
            'metodo_pago' => 'tarjeta',
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 2,
                    'precio' => 25.00,
                ],
            ],
        ];

        $this->postJson('/api/ventas', $data)
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'numero', 'total'],
            ]);

        $this->assertEquals(8, $producto->fresh()->stock);
    }

    public function test_obtener_venta(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $venta = Venta::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->getJson("/api/ventas/{$venta->id}")
            ->assertOk()
            ->assertJson([
                'data' => ['id' => $venta->id],
            ]);
    }
}
```

## Tests de Servicio

```php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Venta;
use App\Services\DocumentGeneration\DocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    private DocumentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DocumentService::class);
    }

    public function test_generar_recibo_pdf(): void
    {
        $venta = Venta::factory()
            ->withDetalles(3)
            ->create();

        $pdf = $this->service->generarRecibo($venta, 'pdf');

        $this->assertNotNull($pdf);
    }

    public function test_generar_recibo_word(): void
    {
        $venta = Venta::factory()
            ->withDetalles(3)
            ->create();

        $docx = $this->service->generarRecibo($venta, 'word');

        $this->assertNotNull($docx);
    }

    public function test_generar_certificado(): void
    {
        $datos = [
            'empleado' => 'Juan Pérez',
            'dni' => '12345678',
            'cargo' => 'Vendedor',
            'fecha_inicio' => '2024-01-01',
            'remuneracion' => 2000.00,
        ];

        $certificado = $this->service->generarCertificado($datos, 'word');

        $this->assertNotNull($certificado);
    }
}
```

## Factory para Venta

```php
<?php

namespace Database\Factories;

use App\Models\{
    Venta,
    User,
    Cliente,
};
use Illuminate\Database\Eloquent\Factories\Factory;

class VentaFactory extends Factory
{
    protected $model = Venta::class;

    public function definition(): array
    {
        return [
            'numero' => Venta::generarNumero(),
            'user_id' => User::factory(),
            'cliente_id' => Cliente::factory(),
            'subtotal' => $this->faker->randomFloat(2, 10, 1000),
            'igv' => fn (array $attributes) => $attributes['subtotal'] * 0.18,
            'total' => fn (array $attributes) => $attributes['subtotal'] + $attributes['igv'],
            'metodo_pago' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'estado' => 'completada',
        ];
    }

    public function withDetalles(int $cantidad = 3): static
    {
        return $this->afterCreating(function (Venta $venta) use ($cantidad) {
            \App\Models\Producto::factory()
                ->count($cantidad)
                ->create()
                ->each(function ($producto) use ($venta) {
                    $venta->detalles()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => rand(1, 5),
                        'precio_unitario' => $producto->precio_venta,
                    ]);
                });
            
            $venta->calcularTotales();
        });
    }
}
```

## Ejecutar Tests

```bash
# Todos los tests
docker compose exec app php artisan test

# Tests unitarios
docker compose exec app php artisan test --testsuite=Unit

# Tests de feature
docker compose exec app php artisan test --testsuite=Feature

# Test específico
docker compose exec app php artisan test --filter=VentaTest

# Con coverage
docker compose exec app php artisan test --coverage

# Verbose
docker compose exec app php artisan test -v
```

## Mejores Prácticas

1. **Usar RefreshDatabase** para tests de base de datos
2. **Crear factories** para datos de prueba
3. **Usar actingAs** para autenticar usuarios
4. **Asserts específicos** en lugar de asserts genéricos
5. **Tests aislados** que no dependen de otros tests
6. **Nombres descriptivos** que expliquen qué se teste
7. **setUp()** para configuración común
