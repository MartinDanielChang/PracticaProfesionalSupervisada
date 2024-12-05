<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colegiacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ColegiacionController extends Controller
{
    /**
     * Muestra el formulario de colegiación.
     */
    public function index()
    {
        return view('colegiacion.index'); // Cambia el nombre de la vista según tu estructura
    }

    /**
     * Guarda la información de la colegiación y el archivo PDF.
     */
    public function subirArchivo(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:15360',
            'preinscripcion' => 'required|string|max:255',
            'empresaPractica' => 'nullable|string|max:255',
            'labora' => 'required|string|max:2',
            'unidadDepartamento' => 'nullable|string|max:255',
            'direccionExacta' => 'nullable|string|max:255',
            'celularEmpresa' => 'nullable|string|max:20',
            'telefonoFijo' => 'nullable|string|max:20',
            'extension' => 'nullable|string|max:10',
            'correoEmpresa' => 'nullable|email|max:255',
            'cargo' => 'nullable|string|max:255',
            'fechaIngreso' => 'nullable|date',
        ]);
    
        try {
            // Inicializar nombre del archivo
            $nombreArchivo = null;
    
            // Subir el archivo
            if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
                $archivo = $request->file('archivo');
                $nombreArchivo = Auth::user()->id . '_' . time() . '_' . $archivo->getClientOriginalName();
    
                // Intentar almacenar el archivo
                $rutaArchivo = $archivo->storeAs('colegiaciones', $nombreArchivo, 'public');
                
                if (!$rutaArchivo) {
                    throw new \Exception("Error al intentar guardar el archivo en el almacenamiento.");
                }
            } else {
                throw new \Exception("Archivo inválido o no recibido correctamente.");
            }
    
            // Guardar en la base de datos
            Colegiacion::create([
                'preinscripcion' => $validated['preinscripcion'],
                'empresaPractica' => $validated['empresaPractica'],
                'labora' => $validated['labora'],
                'unidadDepartamento' => $validated['unidadDepartamento'],
                'direccionExacta' => $validated['direccionExacta'],
                'celularEmpresa' => $validated['celularEmpresa'],
                'telefonoFijo' => $validated['telefonoFijo'],
                'extension' => $validated['extension'],
                'correoEmpresa' => $validated['correoEmpresa'],
                'cargo' => $validated['cargo'],
                'fechaIngreso' => $validated['fechaIngreso'],
                'estado_nota' => 'INGRESADA',
                'fecha_ingreso' => now(),
                'user_id' => auth()->user()->id,
                'nombre_archivo' => $nombreArchivo,
            ]);
    
            return redirect()->back()->with('success', 'Solicitud guardada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar la solicitud: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al guardar la solicitud.');
        }
    }
    
}
