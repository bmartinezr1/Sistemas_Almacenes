<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'sku',
        'codigo_barras',
        'nombre',
        'descripcion',
        'categoria_id',
        'precio_venta',
        'costo',
        'unidad',
        'stock_minimo',
        'cantidad_actual',
        'activo'
    ]

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'producto_id');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }
}
