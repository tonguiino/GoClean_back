<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicios::with(['usuario', 'socio'])->get();
        return response()->json($servicios);
    }

    /**fd
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'socio_id' => 'required|exists:usuarios,id',
            'descripcion' => 'required|string',
            'estado' => 'required|in:Pendiente,En curso,Finalizado,Cancelado',
        ]);

        $servicio = Servicios::create($request->all());

        return response()->json(['message' => 'Servicio creado exitosamente', 'servicio' => $servicio], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $servicio = Servicios::with(['usuario', 'socio'])->findOrFail($id);
        return response()->json($servicio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'string',
            'estado' => 'in:Pendiente,En curso,Finalizado,Cancelado'
        ]);

        $servicio = Servicios::findOrFail($id);
        $servicio->update($request->all());

        return response()->json(['message' => 'Servicio actualizado exitosamente', 'servicio' => $servicio]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $servicio = Servicios::findOrFail($id);
        $servicio->delete();

        return response()->json(['message' => 'Servicio eliminado exitosamente']);
    }
}