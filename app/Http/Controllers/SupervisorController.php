<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\SupervisorEstudiantes;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    /**
     * Mostrar la lista de supervisores con estudiantes asignados.
     */
    public function index()
{
    // Supervisores habilitados con al menos un estudiante asignado
    $habilitados = Supervisor::with(['estudiantes.estudiante:id,name'])
        ->where('estado', 'HABILITADO')
        ->whereHas('estudiantes') // Verifica que tengan estudiantes asignados
        ->get();

    // Supervisores inhabilitados (sin importar los estudiantes)
    $inhabilitados = Supervisor::where('estado', 'INHABILITADO')->get();

    return view('supervisores.index', compact('habilitados', 'inhabilitados'));
}

    /**
     * Habilitar un supervisor.
     */
  /**
 * Habilitar un supervisor.
 */
public function habilitar($id)
{
    $supervisor = Supervisor::findOrFail($id);

    DB::transaction(function () use ($supervisor) {
        // Cambiar el estado a habilitado
        $supervisor->update(['estado' => 'HABILITADO']);

        // Asignar estudiantes automáticamente
        $supervisor->asignarEstudiantes();
    });

    return redirect()->route('supervisores.index')->with('success', 'Supervisor habilitado correctamente y estudiantes asignados.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'estado' => 'required|string|in:HABILITADO,INHABILITADO',
    ]);

    $supervisor = Supervisor::findOrFail($id);

    DB::transaction(function () use ($supervisor, $request) {
        $supervisor->update(['estado' => $request->estado]);

        if ($request->estado === 'HABILITADO') {
            $supervisor->asignarEstudiantes();
        }
    });

    return redirect()->route('supervisores.index')->with('success', 'Supervisor actualizado correctamente.');
}



    /**
     * Inhabilitar un supervisor.
     */
   /**
 * Inhabilitar un supervisor y eliminar los estudiantes asignados.
 */
public function inhabilitar($id)
{
    $supervisor = Supervisor::findOrFail($id);

    // Eliminar las relaciones de estudiantes asignados
    $supervisor->estudiantes()->delete();

    // Actualizar el estado del supervisor
    $supervisor->update([
        'estado' => 'INHABILITADO',
        'no_estudiantes' => 0 // Reiniciar el contador
    ]);

    return redirect()->route('supervisores.index')->with('success', 'Supervisor inhabilitado correctamente y estudiantes eliminados.');
}

    /**
     * Crear un supervisor manualmente.
     */
    public function store(Request $request)
    {
        // Validar el formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
        ]);

        // Crear el supervisor
        Supervisor::create([
            'nombre' => $request->nombre,
            'contacto' => $request->contacto,
            'no_estudiantes' => 0, // Inicialmente sin estudiantes asignados
        ]);

        return redirect()->route('supervisores.index')->with('success', 'Supervisor creado exitosamente.');
    }

    /**
     * Crear un supervisor automáticamente al crear un usuario con rol 3.
     */
    public static function crearSupervisorAutomatico($user)
    {
        if ($user->COD_ROL == 3) {
            Supervisor::create([
                'nombre' => $user->name,
                'contacto' => $user->email,
                'no_estudiantes' => 0, // Inicialmente sin estudiantes asignados
            ]);
        }
    }

    /**
     * Subir archivos de supervisión para los estudiantes asignados.
     */
    public function subirArchivo(Request $request, $id)
    {
        $validated = $request->validate([
            'supervision1_file' => 'required|file|mimes:pdf',
            'supervision2_file' => 'nullable|file|mimes:pdf',
        ]);

        $asignacion = SupervisorEstudiantes::findOrFail($id);

        if ($request->hasFile('supervision1_file') && !$asignacion->supervision1_file) {
            $validated['supervision1_file'] = $request->file('supervision1_file')->store('supervision_files', 'public');
        }

        if ($request->hasFile('supervision2_file') && !$asignacion->supervision2_file) {
            $validated['supervision2_file'] = $request->file('supervision2_file')->store('supervision_files', 'public');
        }

        $asignacion->update($validated);

        return redirect()->back()->with('success', 'Archivos subidos correctamente.');
    }

    /**
     * Mostrar estudiantes asignados por supervisores.
     */
    public function misEstudiantes()
{
    $user = auth()->user();

    if ($user->COD_ROL === 1) { // Usuario administrador
        // Supervisores habilitados con al menos un estudiante asignado
        $supervisores = Supervisor::with(['estudiantes.estudiante'])
            ->where('estado', 'HABILITADO') // Solo habilitados
            ->whereHas('estudiantes') // Supervisores con estudiantes asignados
            ->get();
    } elseif ($user->COD_ROL === 3 && $user->supervisor) { // Usuario supervisor
        // Verificar si el supervisor está habilitado
        $supervisor = Supervisor::with(['estudiantes.estudiante'])
            ->where('id_supervisor', $user->supervisor->id_supervisor)
            ->where('estado', 'HABILITADO') // Solo habilitados
            ->first();

        if (!$supervisor) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $supervisores = collect([$supervisor]);
    } else {
        return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
    }

    return view('supervisores.mis_estudiantes', compact('supervisores'));
}


     /**
     * Procesar estudiantes con estado FINALIZADA.
     */
    public function procesarFinalizados()
    {
        $supervisores = Supervisor::all();

        foreach ($supervisores as $supervisor) {
            $supervisor->procesarEstudiantesFinalizados();
        }

        return redirect()->route('supervisores.index')->with('success', 'Estudiantes finalizados procesados correctamente.');
    }

    /**
     * Actualizar la cantidad máxima de estudiantes por supervisor.
     */
    public function actualizarMaxEstudiantes(Request $request, $id)
    {
        $request->validate([
            'max_estudiantes' => 'required|integer|min:1',
        ]);

        $supervisor = Supervisor::findOrFail($id);
        $supervisor->update(['max_estudiantes' => $request->max_estudiantes]);

        return redirect()->route('supervisores.index')->with('success', 'Máximo de estudiantes actualizado correctamente.');
    }
}
