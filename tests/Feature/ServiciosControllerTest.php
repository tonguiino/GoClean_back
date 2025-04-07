<?php

namespace Tests\Feature;

use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiciosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_devuelve_lista_de_servicios()
    {
        Servicios::factory()->count(3)->create();

        $response = $this->getJson('/api/servicios');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id', 'id_usuario', 'id_socio', 'descripcion', 'tipo_plan', 'estado', 'direccion_servicio', 'fecha_solicitud', 'fecha_finalizacion', 'created_at', 'updated_at'
                     ]
                 ]);
    }

    public function test_store_crea_un_servicio()
    {
        $usuario = Usuarios::factory()->create();
        $socio = Usuarios::factory()->create();

        $data = [
            'id_usuario' => $usuario->id,
            'id_socio' => $socio->id,
            'descripcion' => 'Servicio de limpieza',
            'tipo_plan' => 'Mañana',
            'estado' => 'Pendiente',
            'direccion_servicio' => 'Calle Falsa 123',
        ];

        $response = $this->postJson('/api/servicios', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Servicio creado exitosamente',
                     'servicio' => [
                         'descripcion' => 'Servicio de limpieza',
                         'tipo_plan' => 'Mañana',
                         'estado' => 'Pendiente',
                     ]
                 ]);

        $this->assertDatabaseHas('servicios', $data);
    }

    public function test_show_devuelve_un_servicio()
    {
        $servicio = Servicios::factory()->create();

        $response = $this->getJson("/api/servicios/{$servicio->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $servicio->id,
                     'descripcion' => $servicio->descripcion,
                 ]);
    }

    public function test_update_actualiza_un_servicio()
    {
        $servicio = Servicios::factory()->create();

        $data = [
            'descripcion' => 'Servicio actualizado',
            'estado' => 'En curso',
        ];

        $response = $this->putJson("/api/servicios/{$servicio->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Servicio actualizado exitosamente',
                     'servicio' => $data,
                 ]);

        $this->assertDatabaseHas('servicios', $data);
    }

    public function test_destroy_elimina_un_servicio()
    {
        $servicio = Servicios::factory()->create();

        $response = $this->deleteJson("/api/servicios/{$servicio->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Servicio eliminado exitosamente',
                 ]);

        $this->assertDatabaseMissing('servicios', ['id' => $servicio->id]);
    }
}
