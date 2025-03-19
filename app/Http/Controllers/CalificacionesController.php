<?php

namespace App\Http\Controllers;

use App\Models\Calificaciones;
use Illuminate\Http\Request;

class CalificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response ()->json(Calificaciones::all(), 200);//retorna todas las calificaciones
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)//crea una calificacion
    {
        $request->validate([
            'id_servicio'=>'required|exists:servicios,id',
            'id_usuario'=>'required|exists:usuarios,id',
            'calificacion'=>'required|numeric|min:0|max:5',
            'comentario'=>'nullable|string',
        ]);

        $calificaciones=Calificaciones::create($request->all());
        return response()->json($calificaciones, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)//muestra una calificacion en especifico por su id
    {
        $calificaciones=Calificaciones::findOrfail($id);
        return response()->json($calificaciones, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calificaciones $calificaciones)//actualiza una calificacion 
    {
        $request->validate([
            'calificacion'=>'numeric|min:0|max:5',
            'comentario'=>'nullable|string'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)//elimina una calificacion
    {
        Calificaciones::destroy($id);
        return response()->json(null, 204);
    }
}
