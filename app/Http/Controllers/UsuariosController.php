<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class UsuariosController extends Controller
{
    public function index()
    {
        // Este bloque de codigo contiene la logica para el metodo index a demas de tener manejo de errores
        try {
            $usuarios = Usuarios::all();
            return response()->json([
                'status' => 'succes',
                'data' => $usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener ususarios',
                'message' =>  $th->getMessage(), //Esta linea nos indicara el mensaje del cual origina el error
                'line' => $th->getLine(), //Esta linea nos dice en que linea exacta sucedio el error
                'code' => $th->getCode() //Esta linea nos dice el codigo del error si es 404 o 500 o el que sea 
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuarios,correo', //Si al utilizar el meotdo actualziar 'update' este nos genera algun error debemos poner 'except,id' en update!
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
                'status' => 'Ok',
                'data' => $usuarios
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al agregar ususarios',
                'message' =>  $th->getMessage(), //Esta linea nos indicara el mensaje del cual origina el error
                'line' => $th->getLine(), //Esta linea nos dice en que linea exacta sucedio el error
                'code' => $th->getCode() //Esta linea nos dice el codigo del error si es 404 o 500 o el que sea 
            ]);
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
                'message' => 'Error al mostrar ususario',
                'message' =>  $th->getMessage(), //Esta linea nos indicara el mensaje del cual origina el error
                'line' => $th->getLine(), //Esta linea nos dice en que linea exacta sucedio el error
                'code' => $th->getCode() //Esta linea nos dice el codigo del error si es 404 o 500 o el que sea 
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuarios $usuarios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuarios $usuarios)
    {
        //
    }
}
