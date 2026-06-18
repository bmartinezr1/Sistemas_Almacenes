<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'usuario_id',
        'medio_pago',
        'monto_recibido',
        'estado',
        'motivo_anulacion',
        'usuario_anulacion_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuarioAnulacion()
    {
        return $this->belongsTo(User::class, 'usuario_anulacion_id');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }
}
