<?php

namespace Tests\Feature;

use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuariosControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_devuelve_lista_de_usuarios()
    {
        // Crear usuarios de prueba
        Usuarios::factory()->count(3)->create();

        // Realizar la solicitud GET
        $response = $this->getJson('/api/usuarios');

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'nombre', 'correo', 'telefono', 'direccion', 'sexo', 'rol', 'verificado']
                ]
            ]);
    }

    public function test_store_crea_un_usuario()
    {
        // Datos de prueba
        $data = [
            'nombre' => 'Juan Pérez',
            'correo' => 'juan@example.com',
            'telefono' => '1234567890',
            'direccion' => 'Calle Falsa 123',
            'contrasena' => 'password123',
            'contrasena_confirmation' => 'password123',
            'sexo' => 'Masculino',
            'rol' => 'Cliente',
            'verificado' => true
        ];

        // Realizar la solicitud POST
        $response = $this->postJson('/api/usuarios', $data);

        // Verificar la respuesta
        $response->assertStatus(201)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'nombre' => 'Juan Pérez',
                    'correo' => 'juan@example.com',
                ],
            ]);

        // Verificar que el usuario fue creado en la base de datos
        $this->assertDatabaseHas('usuarios', [
            'nombre' => 'Juan Pérez',
            'correo' => 'juan@example.com'
        ]);

        // Verificar que la contraseña esté correctamente encriptada
        $usuario = Usuarios::where('correo', 'juan@example.com')->first();
        $this->assertTrue(Hash::check('password123', $usuario->contrasena));
    }

    public function test_show_devuelve_un_usuario()
    {
        // Crear un usuario de prueba
        $usuario = Usuarios::factory()->create();

        // Realizar la solicitud GET
        $response = $this->getJson("/api/usuarios/{$usuario->id}");

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'data' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre
                ]
            ]);
    }

    public function test_update_actualiza_un_usuario()
    {
        // Crear un usuario de prueba
        $usuario = Usuarios::factory()->create();

        // Datos para actualizar
        $data = [
            'nombre' => 'Nombre Actualizado',
            'correo' => $usuario->correo,
            'telefono' => $usuario->telefono,
            'rol' => 'Socio'
        ];

        // Realizar la solicitud PUT
        $response = $this->putJson("/api/usuarios/{$usuario->id}", $data);

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'message' => 'Usuario actualizado correctamente',
                'data' => [
                    'nombre' => 'Nombre Actualizado',
                    'rol' => 'Socio'
                ]
            ]);

        // Verificar que los datos fueron actualizados en la base de datos
        $this->assertDatabaseHas('usuarios', [
            'id' => $usuario->id,
            'nombre' => 'Nombre Actualizado',
            'rol' => 'Socio'
        ]);
    }

    public function test_destroy_elimina_un_usuario()
    {
        // Crear un usuario de prueba
        $usuario = Usuarios::factory()->create();

        // Realizar la solicitud DELETE
        $response = $this->deleteJson("/api/usuarios/{$usuario->id}");

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
                'message' => 'Usuario borrado correctamente'
            ]);

        // Verificar que el usuario fue eliminado de la base de datos
        $this->assertDatabaseMissing('usuarios', [
            'id' => $usuario->id
        ]);
    }
}
