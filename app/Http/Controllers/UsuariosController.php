<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index()
    {
        try {
            $usuarios = Usuarios::all();
            return response()->json([
                'status' => 'success',
                'data' => $usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener usuarios',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuarios,correo',
            'telefono' => 'required|string|min:10|max:18|unique:usuarios,telefono',
            'direccion' => 'required|string|max:100',
            'contrasena' => 'required|string|min:8|max:50|confirmed',
            'sexo' => 'string|in:Masculino,Femenino,Otro',
            'rol' => 'required|string|in:Cliente,Socio',
            'verificado' => 'boolean'
        ]);

        try {
            $usuarios = Usuarios::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'contrasena' => Hash::make($request->contrasena),
                'sexo' => $request->sexo,
                'rol' => $request->rol,
                'verificado' => $request->verificado
            ]);

            return response()->json([
                'status' => 'ok',
                'data' => $usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al registrar usuario',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode()
            ], 500);
        }
    }

    public function show(Usuarios $usuario)
    {
        try {
            return response()->json([
                'status' => 'ok',
                'data' => $usuario
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al mostrar usuario',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode()
            ]);
        }
    }

    public function update(Request $request, Usuarios $usuario)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'correo' => 'required|string|email|max:100|unique:usuarios,correo,' . $usuario->id,
                'telefono' => 'required|string|min:10|max:15|unique:usuarios,telefono,' . $usuario->id,
                'direccion' => 'nullable|string|max:255',
                'sexo' => 'nullable|string|in:Masculino,Femenino,Otro',
                'rol' => 'required|string|in:Cliente,Socio',
                'verificado' => 'boolean',
            ]);

            $usuario->update($request->all());

            return response()->json([
                'status' => 'ok',
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar usuario',
                'debug' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Usuarios $usuario)
    {
        try {
            $usuario->delete();
            return response()->json([
                'status' => 'ok',
                'message' => 'Usuario eliminado correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar usuario',
                'debug' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Método para iniciar sesión
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string'
        ]);

        $usuario = Usuarios::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Inicio de sesión exitoso',
            'data' => $usuario
        ], 200);
    }
}
