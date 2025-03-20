<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                'message' => 'Error al obtener ususarios = ',
                'message' =>  $th->getMessage(), //Esta linea nos indicara el mensaje del cual origina el error
                'line' => $th->getLine(), //Esta linea nos dice en que linea exacta sucedio el error
                'code' => $th->getCode() //Esta linea nos dice el codigo del error si es 404 o 500 o el que sea 
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuarios $usuarios)
    {
        //
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
