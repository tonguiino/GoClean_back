<?php

namespace Tests\Feature;

use App\Models\Calificaciones;
use App\Models\Servicios;
use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalificacionesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_devuelve_lista_de_calificaciones()
    {
        Calificaciones::factory()->count(3)->create();

        $response = $this->getJson('/api/calificaciones');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'id_evaluador',
                    'id_evaluado',
                    'id_servicio',
                    'estrellas',
                    'comentarios',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_store_crea_una_calificacion()
    {
        $evaluador = Usuarios::factory()->create();
        $evaluado = Usuarios::factory()->create();
        $servicio = Servicios::factory()->create();

        $data = [
            'id_evaluador' => $evaluador->id,
            'id_evaluado' => $evaluado->id,
            'id_servicio' => $servicio->id,
            'estrellas' => 5,
            'comentarios' => 'Excelente servicio',
        ];

        $response = $this->postJson('/api/calificaciones', $data);

        $response->assertStatus(201)
            ->assertJson([
                'id_evaluador' => $evaluador->id,
                'id_evaluado' => $evaluado->id,
                'id_servicio' => $servicio->id,
                'estrellas' => 5,
                'comentarios' => 'Excelente servicio',
            ]);

        $this->assertDatabaseHas('calificaciones', $data);
    }

    public function test_show_devuelve_una_calificacion()
    {
        $calificacion = Calificaciones::factory()->create();

        $response = $this->getJson("/api/calificaciones/{$calificacion->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $calificacion->id,
                'id_evaluador' => $calificacion->id_evaluador,
                'id_evaluado' => $calificacion->id_evaluado,
                'id_servicio' => $calificacion->id_servicio,
                'estrellas' => $calificacion->estrellas,
                'comentarios' => $calificacion->comentarios,
            ]);
    }

    public function test_update_actualiza_una_calificacion()
    {
        $calificacion = Calificaciones::factory()->create();

        $data = [
            'estrellas' => 4,
            'comentarios' => 'Buen servicio, pero puede mejorar',
        ];

        $response = $this->putJson("/api/calificaciones/{$calificacion->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'estrellas' => 4,
                'comentarios' => 'Buen servicio, pero puede mejorar',
            ]);

        $this->assertDatabaseHas('calificaciones', $data);
    }

    public function test_destroy_elimina_una_calificacion()
    {
        $calificacion = Calificaciones::factory()->create();

        $response = $this->deleteJson("/api/calificaciones/{$calificacion->id}");

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Calificación eliminada correctamente',
            ]);

        $this->assertDatabaseMissing('calificaciones', ['id' => $calificacion->id]);
    }
}
