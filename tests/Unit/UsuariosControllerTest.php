<?php

namespace Tests\Feature;

use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuariosControllerTest extends TestCase
{
    use RefreshDatabase;  // Esto asegura que la base de datos se reinicie después de cada prueba

    // Test para listar todos los usuarios
    public function test_can_list_all_usuarios()
    {
        // Crear algunos usuarios para probar
        Usuarios::factory()->create();
        Usuarios::factory()->create();

        $response = $this->getJson('http://127.0.0.1:8000/api/usuarios');  // Suponiendo que tu ruta de listar usuarios es '/api/usuarios'

        $response->assertStatus(200);  // Esperamos una respuesta con estado 200
        $response->assertJsonStructure([  // Validar la estructura de la respuesta
            'status',
            'data' => [
                '*' => [
                    'id',
                    'nombre',
                    'correo',
                    'telefono',
                    'direccion',
                    'contrasena',
                    'sexo',
                    'rol',
                    'verificado',
                ],
            ],
        ]);
    }

    // Test para crear un usuario
    public function test_can_create_a_user()
    {
        $data = [
            'nombre' => 'Juan Pérez',
            'correo' => 'juan@example.com',
            'telefono' => '1234567890',
            'direccion' => 'Calle Ficticia 123',
            'contrasena' => 'password123',
            'contrasena_confirmation' => 'password123',
            'sexo' => 'Masculino',
            'rol' => 'Cliente',
            'verificado' => true,
        ];

        $response = $this->postJson('http://127.0.0.1:8000/api/usuarios', $data);  // Suponiendo que tu ruta para crear es '/api/usuarios'

        $response->assertStatus(200);  // Esperamos que la respuesta sea exitosa
        $response->assertJson(['status' => 'Ok']);  // Verificamos el mensaje de éxito
        $this->assertDatabaseHas('usuarios', [  // Verificamos que el usuario fue creado en la base de datos
            'correo' => 'juan@example.com',
        ]);
    }

    // Test para mostrar un usuario específico
    public function test_can_show_a_user()
    {
        // Crear un usuario para probar
        $usuario = Usuarios::factory()->create();

        $response = $this->getJson('http://127.0.0.1:8000/api/usuarios/' . $usuario->id);  // Suponiendo que la ruta para mostrar es '/api/usuarios/{id}'

        $response->assertStatus(200);  // Esperamos que la respuesta sea exitosa
        $response->assertJsonStructure([  // Validamos la estructura de la respuesta
            'status',
            'data' => [
                'id',
                'nombre',
                'correo',
                'telefono',
                'direccion',
                'contrasena',
                'sexo',
                'rol',
                'verificado',
            ],
        ]);
    }

    // Test para actualizar un usuario
    public function test_can_update_a_user()
    {
        // Crear un usuario para probar
        $usuario = Usuarios::factory()->create();

        $data = [
            'nombre' => 'Juan Pérez Actualizado',
            'correo' => 'juan_actualizado@example.com',
            'telefono' => '987654321',
            'direccion' => 'Calle Ficticia 456',
            'contrasena' => 'newpassword123',
            'sexo' => 'Masculino',
            'rol' => 'Cliente',
            'verificado' => true,
        ];

        $response = $this->putJson('http://127.0.0.1:8000/api/usuarios/' . $usuario->id, $data);  // Suponiendo que la ruta para actualizar es '/api/usuarios/{id}'

        $response->assertStatus(200);  // Esperamos que la respuesta sea exitosa
        $response->assertJson(['status' => 'ok']);  // Verificamos el mensaje de éxito
        $this->assertDatabaseHas('usuarios', [  // Verificamos que el usuario fue actualizado en la base de datos
            'correo' => 'juan_actualizado@example.com',
            'nombre' => 'Juan Pérez Actualizado',
        ]);
    }

    // Test para eliminar un usuario
    public function test_can_delete_a_user()
    {
        // Crear un usuario para probar
        $usuario = Usuarios::factory()->create();

        $response = $this->deleteJson('http://127.0.0.1:8000/api/usuarios/' . $usuario->id);  // Suponiendo que la ruta para eliminar es '/api/usuarios/{id}'

        $response->assertStatus(200);  // Esperamos que la respuesta sea exitosa
        $response->assertJson(['status' => 'ok']);  // Verificamos el mensaje de éxito
        $this->assertDatabaseMissing('usuarios', [  // Verificamos que el usuario haya sido eliminado de la base de datos
            'id' => $usuario->id,
        ]);
    }
}
