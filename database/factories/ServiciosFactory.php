<?php

namespace Database\Factories;

use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiciosFactory extends Factory
{
    protected $model = Servicios::class;

    public function definition()
    {
        return [
            'id_usuario' => Usuarios::factory(), // Relación con un usuario
            'id_socio' => Usuarios::factory(),   // Relación con un socio
            'descripcion' => $this->faker->sentence,
            'tipo_plan' => $this->faker->randomElement(['Mañana', 'Tarde', 'Día completo']),
            'estado' => $this->faker->randomElement(['Pendiente', 'En curso', 'Finalizado', 'Cancelado']),
            'direccion_servicio' => $this->faker->address,
            'fecha_solicitud' => now(),
            'fecha_finalizacion' => $this->faker->optional()->dateTimeBetween('now', '+1 week'),
        ];
    }
}
