<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finalizacion;
use Illuminate\Support\Facades\Auth;

class FinalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function subirFinalizacion(Request $request)
    {
        // Validar archivo
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:15360',
            'fechaIngreso' => 'nullable|date',
        ]);
    
        try {
            // Subir archivo
            $archivo = $request->file('archivo');

            // Obtener el correo del usuario autenticado
            $correoUsuarioAutenticado = Auth::user()->email;

            // Generar el nombre del archivo con el correo del usuario y el nombre original del archivo
            $nombreArchivo = $correoUsuarioAutenticado . '_' . $archivo->getClientOriginalName();
            
            // Almacenar en la carpeta 'presentaciones' dentro del disco 'public'
            $archivo->storeAs('finalizacion', $nombreArchivo, 'public');
    
            // Guardar en base de datos
            Finalizacion::create([
                'user_id' => auth()->user()->id,
                'estado_nota' => 'INGRESADA',
                'fecha_ingreso' => $request->input('fechaIngreso', now()),
            ]);
    
            return redirect()->back()->with('success', 'Archivo subido y datos guardados correctamente.');
        } catch (\Exception $e) {
            // Manejar errores y redirigir con mensaje de error
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al subir el archivo. Inténtalo de nuevo.']);
        }
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
        //
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
