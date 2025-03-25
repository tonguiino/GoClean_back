<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'contrasena',
        'sexo',
        'rol',
        'verificado'
    ];
}
