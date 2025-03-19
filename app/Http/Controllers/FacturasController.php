<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use Illuminate\Http\Request;

class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Facturas::all(), 200);//retorna todas las facturas
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)//crea una factura
    {
        $request->validate([
            'id_servicio'=>'required|exists:servicios,id',
            'id_usuario'=>'required|exists:usuarios,id',
            'id_socio'=>'required|exists:usuarios,id',
            'monto'=>'required|numeric|min:0',
            'metodo_pago'=>'required|in:TDC,PSE,Efectivo',
            'detalles'=>'nullable',
        ]);

        $facturas=Facturas::create($request->all());
        return response()->json($facturas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $facturas=Facturas::findOrfail($id);
        return response()->json($facturas, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facturas $facturas)//actualiza una factura
    {
        $request->validate([
            'monto'=>'numeric|min:0',
            'metodo_pago'=>'in:TDC,PSE,Efectivo',
            'detalles'=>'nullable|string'
        ]);

        $facturas->update($request->all());
        return response()->json($facturas, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)//elimina una factura
    {
        Facturas::destroy($id);
        return response()->json(null, 204);
    }
}
