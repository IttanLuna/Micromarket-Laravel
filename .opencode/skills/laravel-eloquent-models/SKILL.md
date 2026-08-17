---
name: laravel-eloquent-models
description: "Crear y mantener modelos Eloquent en Laravel con relaciones, scopes, accessors, mutators y casts. Incluye mejores prácticas para el MicroMarket."
---

# Laravel Eloquent Models Skill

Crea modelos Eloquent robustos para el MicroMarket.

## Modelos Base

### Cliente

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'email',
        'telefono',
        'direccion',
    ];

    // Relaciones
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido', 'like', "%{$termino}%")
              ->orWhere('dni', 'like', "%{$termino}%");
        });
    }

    // Accessors
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Mutators
    public function setNombreAttribute(string $value): void
    {
        $this->attributes['nombre'] = ucfirst($value);
    }
}
```

### Producto

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'sku',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'categoria_id',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ventaDetalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }

    // Scopes
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn('stock', '<', 'stock_minimo');
    }

    public function scopeSinStock($query)
    {
        return $query->where('stock', 0);
    }

    // Accessors
    public function getMargenAttribute(): float
    {
        if ($this->precio_compra == 0) return 0;
        
        return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
    }

    public function getEstadoStockAttribute(): string
    {
        if ($this->stock == 0) return 'sin_stock';
        if ($this->stock < $this->stock_minimo) return 'bajo';
        return 'ok';
    }

    // Métodos
    public function reducirStock(int $cantidad): bool
    {
        if ($this->stock < $cantidad) {
            return false;
        }

        $this->decrement('stock', $cantidad);
        return true;
    }

    public function aumentarStock(int $cantidad): void
    {
        $this->increment('stock', $cantidad);
    }
}
```

### Venta

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'cliente_id',
        'user_id',
        'subtotal',
        'igv',
        'total',
        'metodo_pago',
        'monto_entregado',
        'vuelto',
        'estado',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_entregado' => 'decimal:2',
        'vuelto' => 'decimal:2',
    ];

    // Relaciones
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function recibo(): HasOne
    {
        return $this->hasOne(Recibo::class);
    }

    // Scopes
    public function scopeHoy($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeDelMes($query, int $mes, int $ano)
    {
        return $query->whereMonth('created_at', $mes)
                     ->whereYear('created_at', $ano);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($venta) {
            $venta->numero = self::generarNumero();
        });

        static::created(function ($venta) {
            // Reducir stock de productos
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->reducirStock($detalle->cantidad);
            }
        });
    }

    // Métodos
    public static function generarNumero(): string
    {
        $ano = date('Y');
        $consecutivo = self::whereYear('created_at', $ano)->count() + 1;
        
        return sprintf('VTA-%s-%05d', $ano, $consecutivo);
    }

    public function calcularTotales(): void
    {
        $this->subtotal = $this->detalles->sum('subtotal');
        $this->igv = $this->subtotal * 0.18;
        $this->total = $this->subtotal + $this->igv;
        
        if ($this->monto_entregado) {
            $this->vuelto = $this->monto_entregado - $this->total;
        }
        
        $this->save();
    }
}
```

### VentaDetalle

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detalle) {
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
        });

        static::updating(function ($detalle) {
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
        });
    }
}
```

## Buenas Prácticas

1. **Siempre usar `$fillable`** para proteger contra mass assignment
2. **Definir `$casts`** para tipos de datos correctos
3. **Crear scopes** para consultas comunes
4. **Usar accessors** para datos calculados
5. **Crear mutators** para transformar datos
6. **Eager loading** para evitar N+1
7. **Boot methods** para lógica automática
