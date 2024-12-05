<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudPPS;
use App\Models\Colegiacion;
use App\Models\Presentacion;
use App\Models\PPSIA01;
use App\Models\Aceptacion;
use App\Models\SupervisorEstudiantes;
use App\Models\Cancelacion;
use App\Models\Finalizacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SolicitudesPPSController extends Controller
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

        // Recupera el tipo_practica de la primera solicitud del usuario desde la tabla 'solicitudes'
        $tipo_practica = SolicitudPPS::where('user_id', $userId)
            ->first() // Recupera la primera solicitud
            ->tipo_practica ?? 'Normal'; // Si no tiene solicitudes, usar 'Normal'

        // Obtiene las solicitudes del usuario donde tipo_practica es igual al tipo_practica recuperado
        $solicitudes = SolicitudPPS::where('user_id', $userId)
            ->where('tipo_practica', $tipo_practica)
            ->get();

        $estado_solicitud = $solicitudes->pluck('estado_solicitud');

        // Consulta las colegiaciones del usuario y extrae el estado de la nota
        $estado_nota_colegiacion = Colegiacion::where('user_id', $userId)
            ->pluck('estado_nota');

        // Relaciona con la tabla Presentacion filtrando por tipo_practica
        $estado_nota_presentacion = Presentacion::where('user_id', $userId)
            ->whereHas('solicitudPPS', function ($query) use ($tipo_practica) {
                $query->where('tipo_practica', $tipo_practica);
            })
            ->pluck('estado_nota');

        // Relaciona con la tabla PPSIA01 filtrando por tipo_practica
        $estado_nota_ppsia01 = PPSIA01::where('user_id', $userId)
            ->whereHas('solicitudPPS', function ($query) use ($tipo_practica) {
                $query->where('tipo_practica', $tipo_practica);
            })
            ->pluck('estado_nota');

        // Relaciona con la tabla Aceptacion filtrando por tipo_practica
        $estado_nota_aceptacion = Aceptacion::where('user_id', $userId)
            ->whereHas('solicitudPPS', function ($query) use ($tipo_practica) {
                $query->where('tipo_practica', $tipo_practica);
            })
            ->pluck('estado_nota');

        // Relaciona con la tabla Finalizacion filtrando por tipo_practica
        $estado_nota_finalizacion = Finalizacion::where('user_id', $userId)
            ->whereHas('solicitudPPS', function ($query) use ($tipo_practica) {
                $query->where('tipo_practica', $tipo_practica);
            })
            ->pluck('estado_nota');

        // Retorna la vista con las variables necesarias
        return view('solicitudes.index', compact(
            'solicitudes',
            'estado_solicitud',
            'estado_nota_colegiacion',
            'estado_nota_presentacion',
            'estado_nota_ppsia01',
            'estado_nota_aceptacion',
            'estado_nota_finalizacion',
            'tipo_practica' // Pasamos 'tipo_practica' para usarlo en la vista
        ));
    }

    /**
     * Cambiar el estado de la solicitud a "APROBADA" o "RECHAZADA".
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validar el estado proporcionado
            $estado = $request->input('estado');
    
            if (!in_array($estado, ['APROBADA', 'FINALIZADA', 'CANCELADA'])) {
                return response()->json(['error' => 'Estado no válido'], 400);
            }
    
            // Obtener la solicitud
            $solicitud = SolicitudPPS::findOrFail($id);
    
            // Actualizar el estado de la solicitud
            $solicitud->estado_solicitud = $estado;
            $solicitud->save();
    
            // Procesar automáticamente cuando el estado es FINALIZADO
            if ($estado === 'FINALIZADA') {
                $this->procesarFinalizacionEstudiante($solicitud->user_id);
            }
    
            return redirect()->back()->with('success', "Estado de la solicitud actualizado a $estado.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function procesarFinalizacionEstudiante($estudianteId)
    {
        // Encontrar la asignación del estudiante
        $asignacion = SupervisorEstudiantes::where('estudiante_id', $estudianteId)->first();

        if ($asignacion) {
            $supervisor = $asignacion->supervisor;

            // Eliminar la asignación del estudiante
            $asignacion->delete();

            // Decrementar el contador de estudiantes asignados
            $supervisor->decrement('no_estudiantes');

            // Reasignar nuevos estudiantes automáticamente
            $supervisor->asignarEstudiantes();
        }
    }


    /**
     * Descargar el archivo asociado a una solicitud.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $solicitud = SolicitudPPS::findOrFail($id);

        if ($solicitud->archivo_path && Storage::exists($solicitud->archivo_path)) {
            return Storage::download($solicitud->archivo_path);
        }

        return redirect()->back()->with('error', 'El archivo no existe o no está disponible.');
    }

    /**
     * Guardar una nueva solicitud utilizando un procedimiento almacenado.
     *
     * @param \Illuminate\Http\Request $request
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
            $tipo = 'Normal';

            // Llamada al procedimiento almacenado para insertar en tbl_solicitudes
            DB::statement('CALL insertSolicitud_normal(?, ?, ?, ?, ?)', [
                $estado,
                $fecha,
                $descripcion,
                $tipo,
                $userId
            ]);

            return response()->json(['success' => 'Solicitud creada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la solicitud', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verificar si el usuario tiene registros en tablas relacionadas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanTables()
    {
        $userId = Auth::id();

        // Consultar cada tabla para verificar si el usuario existe en ella
        $tables = [
            'tblcolegiacion' => DB::table('tbl_colegiacion')->where('user_id', $userId)->exists(),
            'tblpresentacion' => DB::table('tbl_nota_presentacion')->where('user_id', $userId)->exists(),
            'tblia01' => DB::table('tbl_ppsia01')->where('user_id', $userId)->exists(),
            'tblaceptacion' => DB::table('tbl_nota_aceptacion')->where('user_id', $userId)->exists(),
        ];

        return response()->json($tables);
    }
}
