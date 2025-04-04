<?php

namespace Database\Factories;
use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuarios>
 */
class UsuariosFactory extends Factory
{

    protected $model = Usuarios::class;
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'correo' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->unique()->phoneNumber,
            'direccion' => $this->faker->address,
            'sexo' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'rol' => $this->faker->randomElement(['Cliente', 'Socio']),
            'verificado' => $this->faker->boolean,
            'contrasena' => bcrypt('password'), // Contraseña por defecto
        ];
    }
}
