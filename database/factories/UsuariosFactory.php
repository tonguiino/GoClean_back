<?php

namespace Database\Factories;

use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuariosFactory extends Factory
{
    protected $model = Usuarios::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name,
            'correo' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'contrasena' => bcrypt('password123'),
            'sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'rol' => $this->faker->randomElement(['Cliente', 'Socio']),
            'verificado' => $this->faker->boolean(),
        ];
    }
}
