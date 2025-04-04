<?php

namespace Database\Factories;

use App\Models\Facturas;
use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facturas>
 */
class FacturasFactory extends Factory
{
    protected $model = Facturas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_servicio' => Servicios::factory(),
            'id_usuario' => Usuarios::factory(),
            'id_socio' => Usuarios::factory(),
            'monto' => $this->faker->randomFloat(2, 50, 500),
            'metodo_pago' => $this->faker->randomElement(['TDC', 'PSE', 'Efectivo']),
            'detalles' => $this->faker->sentence,
            'fecha_pago' => now(),
        ];
    }
}
