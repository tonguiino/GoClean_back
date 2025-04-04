<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            ], 500);
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
            'sexo' => 'nullable|string|in:Masculino,Femenino,Otro',
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
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Error en el método store:', [
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al agregar usuario',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode(),
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
            ], 500);
        }
    }

    public function update(Request $request, Usuarios $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuarios,correo,' . $usuario->id,
            'telefono' => 'required|string|min:10|max:15|unique:usuarios,telefono,' . $usuario->id,
            'direccion' => 'nullable|string|max:255',
            'sexo' => 'nullable|string|in:Masculino,Femenino,Otro',
            'rol' => 'required|string|in:Cliente,Socio',
            'verificado' => 'boolean',
            'contrasena' => 'nullable|string|min:8|max:50|confirmed'
        ]);

        try {
            $data = $request->only([
                'nombre',
                'correo',
                'telefono',
                'direccion',
                'sexo',
                'rol',
                'verificado'
            ]);

            if ($request->filled('contrasena')) {
                $data['contrasena'] = Hash::make($request->contrasena);
            }

            $usuario->update($data);

            return response()->json([
                'status' => 'ok',
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar usuario',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode(),
            ], 500);
        }
    }

    public function destroy(Usuarios $usuario)
    {
        try {
            $usuario->delete();
            return response()->json([
                'status' => 'ok',
                'message' => 'Usuario borrado correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar usuario',
                'debug' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => $th->getCode(),
            ], 500);
        }
    }
}
