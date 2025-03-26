<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $servicios = Servicios::with(['usuario', 'socio'])->get();
            return response()->json($servicios);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los servicios', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_usuario' => 'required|exists:usuarios,id',
                'id_socio' => 'required|exists:usuarios,id',
                'descripcion' => 'required|string',
                'estado' => 'required|in:Pendiente,En curso,Finalizado,Cancelado',
            ]);

            $servicio = Servicios::create($request->all());

            return response()->json(['message' => 'Servicio creado exitosamente', 'servicio' => $servicio], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear el servicio', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $servicio = Servicios::with(['usuario', 'socio'])->findOrFail($id);
            return response()->json($servicio);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el servicio', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'descripcion' => 'string',
                'estado' => 'in:Pendiente,En curso,Finalizado,Cancelado'
            ]);

            $servicio = Servicios::findOrFail($id);
            $servicio->update($request->all());

            return response()->json(['message' => 'Servicio actualizado exitosamente', 'servicio' => $servicio]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el servicio', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $servicio = Servicios::findOrFail($id);
            $servicio->delete();

            return response()->json(['message' => 'Servicio eliminado exitosamente']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el servicio', 'message' => $e->getMessage()], 500);
        }
    }
}
