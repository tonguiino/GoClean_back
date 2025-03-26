<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'id_usuario', // Coincide con la base de datos
        'id_socio',   // Coincide con la base de datos
        'descripcion',
        'tipo_plan',
        'estado',
        'direccion_servicio',
        'fecha_solicitud',
        'fecha_finalizacion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuario');
    }

    public function socio()
    {
        return $this->belongsTo(Usuarios::class, 'id_socio');
    }
}
