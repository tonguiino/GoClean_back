<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ServiciosController extends Controller
{
    public function index()
    {
        try {
            $servicios = Servicios::with(['usuario', 'socio'])->get();
            return response()->json($servicios);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los servicios', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
{
    try {
        // Validación de los datos
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'tipo_servicio' => 'required|string',
            'direccion' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'descripcion' => 'nullable|string',
        ]);

        // Crear el servicio
        $servicio = new Servicios();
        $servicio->usuario_id = $request->usuario_id;
        $servicio->tipo_servicio = $request->tipo_servicio;
        $servicio->direccion = $request->direccion;
        $servicio->fecha = $request->fecha;
        $servicio->hora = $request->hora;
        $servicio->descripcion = $request->descripcion;
        $servicio->estado = 'Pendiente'; // Estado inicial
        $servicio->save();

        return response()->json(['message' => 'Servicio agendado con éxito.', 'servicio' => $servicio], 201);
    } catch (Exception $e) {
        return response()->json(['error' => 'Error al agendar el servicio', 'message' => $e->getMessage()], 500);
    }
}

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

    // 🔥 NUEVO: obtener servicios por socio (para Dashboard)
    public function serviciosPorSocio($idSocio)
    {
        try {
            $servicios = Servicios::with(['usuario', 'socio'])
                ->where('id_socio', $idSocio)
                ->get();

            return response()->json(['data' => $servicios], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener servicios del socio', 'message' => $e->getMessage()], 500);
        }
    }

    public function aceptarServicio($id)
{
    try {
        $servicio = Servicios::findOrFail($id);
        // Cambiar el estado a "En curso" cuando se acepte
        $servicio->estado = 'En curso';
        $servicio->save();

        return response()->json(['message' => 'Servicio aceptado exitosamente', 'servicio' => $servicio]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Servicio no encontrado'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => 'Error al aceptar el servicio', 'message' => $e->getMessage()], 500);
    }
}

public function rechazarServicio($id)
{
    try {
        $servicio = Servicios::findOrFail($id);
        // Cambiar el estado a "Cancelado" cuando se rechace
        $servicio->estado = 'Cancelado';
        $servicio->save();

        return response()->json(['message' => 'Servicio rechazado exitosamente', 'servicio' => $servicio]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Servicio no encontrado'], 404);
    } catch (Exception $e) {
        return response()->json(['error' => 'Error al rechazar el servicio', 'message' => $e->getMessage()], 500);
    }
}

}
