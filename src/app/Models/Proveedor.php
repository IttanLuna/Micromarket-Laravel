<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'ruc',
        'email',
        'telefono',
        'direccion',
        'contacto',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('ruc', 'like', "%{$termino}%");
        });
    }
}
