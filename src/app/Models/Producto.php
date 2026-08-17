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
        'categoria_id',
        'nombre',
        'descripcion',
        'sku',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
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
