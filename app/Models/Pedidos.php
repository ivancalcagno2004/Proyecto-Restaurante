<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['mesa_id', 'cant_personas', 'estado', 'total'];

    // Deshabilitar timestamps si no usas created_at y updated_at
    //public $timestamps = false;

    // Relación con el modelo Mesas (un pedido pertenece a una mesa)
    public function mesa()
    {
        return $this->belongsTo(Mesas::class, 'mesa_id');
    }

    // Relación con el modelo Productos (un pedido tiene muchos productos)
    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'pedido_detalles', 'pedido_id', 'producto_id')
            ->withPivot('cantidad', 'subtotal')
            ->withTimestamps();
    }
}
