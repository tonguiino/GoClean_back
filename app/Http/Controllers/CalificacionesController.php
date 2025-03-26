<?php

namespace App\Http\Controllers;

use App\Models\Calificaciones;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CalificacionesController extends Controller
{
    /**
     * Mostrar todas las calificaciones.
     */
    public function index()
    {
        try {
            $calificaciones = Calificaciones::all();
            return response()->json($calificaciones, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las calificaciones'], 500);
        }
    }

    /**
     * Crear una nueva calificación.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_servicio' => 'required|exists:servicios,id',
                'id_evaluado' => 'required|exists:usuarios,id',
                'id_evaluador' => 'required|exists:usuarios,id',
                'estrellas' => 'required|numeric|min:0|max:5',
                'comentario' => 'nullable|string'
            ]);

            $calificacion = Calificaciones::create($request->all());
            return response()->json($calificacion, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Datos inválidos', 'detalles' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la calificación'], 500);
        }
    }

    /**
     * Mostrar una calificación por ID.
     */
    public function show($id)
    {
        try {
            $calificacion = Calificaciones::findOrFail($id);
            return response()->json($calificacion, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Calificación no encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener la calificación'], 500);
        }
    }

    /**
     * Actualizar una calificación.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'estrellas' => 'numeric|min:0|max:5',
                'comentario' => 'nullable|string',
            ]);

            $calificacion = Calificaciones::findOrFail($id);
            $calificacion->update($request->all());

            return response()->json($calificacion, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Calificación no encontrada'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Datos inválidos', 'detalles' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la calificación'], 500);
        }
    }

    /**
     * Eliminar una calificación.
     */
    public function destroy($id)
    {
        try {
            $calificacion = Calificaciones::findOrFail($id);
            $calificacion->delete();

            return response()->json(['mensaje' => 'Calificación eliminada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Calificación no encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la calificación'], 500);
        }
    }
}
