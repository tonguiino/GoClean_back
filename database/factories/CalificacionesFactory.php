<?php

namespace Database\Factories;

use App\Models\Calificaciones;
use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalificacionesFactory extends Factory
{
    protected $model = Calificaciones::class;

    public function definition()
    {
        return [
            'id_evaluador' => Usuarios::factory(),
            'id_evaluado' => Usuarios::factory(),
            'id_servicio' => Servicios::factory(),
            'estrellas' => $this->faker->numberBetween(1, 5),
            'comentarios' => $this->faker->sentence,
        ];
    }
}
