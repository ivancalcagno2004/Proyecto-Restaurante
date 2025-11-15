<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $table = 'entrada';
    protected $fillable = ['precio'];

    // Variable estática para almacenar la instancia única
    private static $instance;

    /**
     * Obtener la instancia única del modelo Entrada.
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            // Buscar el registro único en la base de datos
            self::$instance = self::firstOrCreate([], ['precio' => 0]); // Valor predeterminado: 0
        }

        return self::$instance;
    }

    /**
     * Actualizar el precio de entrada.
     */
    public static function setPrecio($precio)
    {
        $instance = self::getInstance();
        $instance->update(['precio' => $precio]);
    }

    /**
     * Obtener el precio de entrada.
     */
    public static function getPrecio()
    {
        return self::getInstance()->precio;
    }
}
