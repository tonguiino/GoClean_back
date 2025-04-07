<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class FacturasController extends Controller
{
    /**
     * Mostrar todas las facturas.
     */
    public function index()
    {
        try {
            $facturas = Facturas::all();
            return response()->json($facturas, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las facturas'], 500);
        }
    }

    /**
     * Crear una nueva factura.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_servicio' => 'required|exists:servicios,id',
                'id_usuario' => 'required|exists:usuarios,id',
                'id_socio' => 'required|exists:usuarios,id',
                'monto' => 'required|numeric|min:0',
                'metodo_pago' => 'required|in:TDC,PSE,Efectivo',
                'detalles' => 'nullable|string',
            ]);

            $factura = Facturas::create($request->all());
            return response()->json($factura, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Datos inválidos', 'detalles' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al crear la factura'], 500);
        }
    }

    /**
     * Mostrar una factura por ID.
     */
    public function show($id)
    {
        try {
            $factura = Facturas::findOrFail($id);
            return response()->json($factura, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener la factura'], 500);
        }
    }

    /**
     * Actualizar una factura.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'monto' => 'numeric|min:0',
                'metodo_pago' => 'in:TDC,PSE,Efectivo',
                'detalles' => 'nullable|string',
            ]);

            $factura = Facturas::findOrFail($id);
            $factura->update($request->all());

            return response()->json($factura, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Datos inválidos', 'detalles' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar la factura'], 500);
        }
    }

    /**
     * Eliminar una factura.
     */
    public function destroy($id)
    {
        try {
            $factura = Facturas::findOrFail($id);
            $factura->delete();

            return response()->json(['mensaje' => 'Factura eliminada correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Factura no encontrada'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la factura'], 500);
        }
    }

    /**
     * Obtener todas las facturas de un socio específico.
     */
    public function porSocio($id)
    {
        try {
            $facturas = Facturas::where('id_socio', $id)->get();

            if ($facturas->isEmpty()) {
                return response()->json(['mensaje' => 'No se encontraron facturas para este socio.'], 404);
            }

            return response()->json($facturas, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las facturas del socio', 'message' => $e->getMessage()], 500);
        }
    }
}
