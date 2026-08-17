<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
