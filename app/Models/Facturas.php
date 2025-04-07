<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    use HasFactory; 
    protected $table = 'facturas';

    protected $fillable = [
        'id_servicio', 'id_usuario', 'id_socio',
        'monto', 'metodo_pago', 'detalles'
    ];

    public function servicio()//relacion de uno a muchos
    {
        return $this->belongsTo(Servicios::class, 'id_servicio');
    }

    public function usuario()//relacion de uno a muchos
    {
        return $this->belongsTo(Usuarios::class, 'id_usuario');
    }

    public function socio()//relacion de uno a muchos
    {
        return $this->belongsTo(Usuarios::class, 'id_socio');
    }

}
