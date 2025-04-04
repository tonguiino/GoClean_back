<?php

namespace Tests\Feature;

use App\Models\Facturas;
use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacturasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_devuelve_lista_de_facturas()
    {
        Facturas::factory()->count(3)->create();

        $response = $this->getJson('/api/facturas');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'id_servicio',
                    'id_usuario',
                    'id_socio',
                    'monto',
                    'metodo_pago',
                    'detalles',
                    'fecha_pago',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_store_crea_una_factura()
    {
        $servicio = Servicios::factory()->create();
        $usuario = Usuarios::factory()->create();
        $socio = Usuarios::factory()->create();

        $data = [
            'id_servicio' => $servicio->id,
            'id_usuario' => $usuario->id,
            'id_socio' => $socio->id,
            'monto' => 150.50,
            'metodo_pago' => 'TDC',
            'detalles' => 'Pago realizado con tarjeta de crédito',
        ];

        $response = $this->postJson('/api/facturas', $data);

        $response->assertStatus(201)
            ->assertJson([
                'id_servicio' => $servicio->id,
                'id_usuario' => $usuario->id,
                'id_socio' => $socio->id,
                'monto' => 150.50,
                'metodo_pago' => 'TDC',
                'detalles' => 'Pago realizado con tarjeta de crédito',
            ]);

        $this->assertDatabaseHas('facturas', $data);
    }

    public function test_show_devuelve_una_factura()
    {
        $factura = Facturas::factory()->create();

        $response = $this->getJson("/api/facturas/{$factura->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $factura->id,
                'id_servicio' => $factura->id_servicio,
                'id_usuario' => $factura->id_usuario,
                'id_socio' => $factura->id_socio,
                'monto' => $factura->monto,
                'metodo_pago' => $factura->metodo_pago,
                'detalles' => $factura->detalles,
            ]);
    }

    public function test_update_actualiza_una_factura()
    {
        $factura = Facturas::factory()->create();

        $data = [
            'monto' => 200.00,
            'metodo_pago' => 'PSE',
            'detalles' => 'Pago actualizado',
        ];

        $response = $this->putJson("/api/facturas/{$factura->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'monto' => 200.00,
                'metodo_pago' => 'PSE',
                'detalles' => 'Pago actualizado',
            ]);

        $this->assertDatabaseHas('facturas', $data);
    }

    public function test_destroy_elimina_una_factura()
    {
        $factura = Facturas::factory()->create();

        $response = $this->deleteJson("/api/facturas/{$factura->id}");

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Factura eliminada correctamente',
            ]);

        $this->assertDatabaseMissing('facturas', ['id' => $factura->id]);
    }
}
