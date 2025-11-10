<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'descripcion', 'precio', 'categoria', 'stock'];

    // Deshabilitar timestamps si no usas created_at y updated_at
    public $timestamps = false;

    // Relación con el modelo Pedidos (un producto puede estar en muchos pedidos)
    public function pedidos()
    {
        return $this->belongsToMany(Pedidos::class, 'pedido_detalles', 'producto_id', 'pedido_id')
            ->withPivot('cantidad', 'subtotal')
            ->withTimestamps();
    }
}
