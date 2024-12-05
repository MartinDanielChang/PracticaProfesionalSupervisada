<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudPPS;
use App\Models\Colegiacion;
use App\Models\Presentacion;
use App\Models\PPSIA02;
use App\Models\Aceptacion;
use App\Models\Cancelacion;
use App\Models\Finalizacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SolicitudesTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    // Obtiene el usuario autenticado
    $userId = auth()->id();
    
    // Obtiene las solicitudes del usuario donde tipo_practica sea 'TRABAJO'
    $solicitudes = SolicitudPPS::where('user_id', $userId)
        ->where('tipo_practica', 'TRABAJO')
        ->get();
    
    // Extrae los estados de las solicitudes
    $estado_solicitud = $solicitudes->pluck('estado_solicitud');
    
    // Consulta las colegiaciones del usuario y extrae el estado de la nota
    $estadocolegiacion = Colegiacion::where('user_id', $userId)
        ->pluck('estado_nota');
    
    // Consulta las presentaciones del usuario y extrae el estado de la nota
    $estadopresentacion = Presentacion::where('user_id', $userId)
        ->pluck('estado_nota');
    
    // Consulta los registros de la tabla tbl_ppsia01 y extrae el estado de la nota
    $estadoppsia02 = PPSIA02::where('user_id', $userId)
        ->pluck('estado');
    
    // Consulta los registros de la tabla tbl_nota_aceptacion y extrae el estado de la nota
    $estadoaceptacion = Aceptacion::where('user_id', $userId)
        ->pluck('estado_nota');
    
    // Consulta los registros de la tabla tbl_nota_cancelacion y extrae el estado de la nota
    $estadofinalizacion = Finalizacion::where('user_id', $userId)
        ->pluck('estado_nota');
    
    // Retorna la vista con las variables necesarias
    return view('solicitudportrabajo.index', compact(
        'solicitudes', 
        'estado_solicitud', 
        'estadocolegiacion', 
        'estadopresentacion', 
        'estadoppsia02', 
        'estadoaceptacion', 
        'estadofinalizacion'
    ));
}


public function scanTables()
{
    $userId = Auth::id();

    // Consultar cada tabla para verificar si el usuario existe en ella
    $tables = [
        'tblcolegiacion' => DB::table('tbl_colegiacion')->where('user_id', $userId)->exists(),
        'tblpresentacion' => DB::table('tbl_nota_presentacion')->where('user_id', $userId)->exists(),
        'tblia02' => DB::table('tbl_ppsia02')->where('user_id', $userId)->exists(),
        'tblaceptacion' => DB::table('tbl_nota_aceptacion')->where('user_id', $userId)->exists(),
    ];

    return response()->json($tables);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    try {
        // Verificar si el usuario está autenticado
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Usuario no autenticado'], 400);
        }

        // Datos predeterminados
        $estado = 'SOLICITADA';
        $fecha = now();
        $descripcion = 'Inicialización de PPS';
        $tipo = 'TRABAJO';
        $noCuenta = $request->input('noCuenta');

        // Llamada al procedimiento almacenado para insertar en tbl_solicitudes
        DB::statement('CALL insertSolicitud_trabajo(?, ?, ?, ?, ?)', [
            $estado,
            $fecha,
            $descripcion,
            $tipo,
            $userId  // Pasar el user_id al procedimiento
        ]);

        // Llamada al procedimiento almacenado para insertar en la tabla users
        DB::statement('CALL insertUserNoCuenta(?, ?)', [
            $userId,
            $noCuenta
        ]);

        return response()->json(['success' => 'Solicitud y No. Cuenta guardados correctamente'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al guardar la solicitud y No. Cuenta', 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
