<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Calificaciones extends Model
{
    use HasFactory;
    protected $table = 'calificaciones';

    protected $fillable = [
        'id_evaluador', 'id_evaluado', 'id_servicio',
        'estrellas', 'comentarios'
    ];

    public function evaluador (){
        return $this->belongsTo(Usuarios::class, 'id_evaluador');
    }
    public function evaluado (){
        return $this->belongsTo(Usuarios::class, 'id_evaluado');
    }
    public function servicio (){
        return $this->belongsTo(Servicios::class, 'id_servicio');
    }
}
