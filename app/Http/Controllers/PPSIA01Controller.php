<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PPSIA01;
use Illuminate\Support\Facades\Auth;

class PPSIA01Controller extends Controller
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

    public function subirIA01(Request $request)
{
    // Verificar datos sin el archivo
    $validatedData = $request->validate([
        'carrera' => 'required|string',
        'practica' => 'required|string',
        'dias' => 'required|string',
        'horario' => 'required|string',
        'fechaInicio' => 'required|date',
        'fechaFinalizacion' => 'required|date',
        'modalidad' => 'required|string',
        'correoVinculacion' => 'required|email',
        'actividad1' => 'required',
        'actividad2' => 'required',
        'actividad3' => 'required',
        'actividad4' => 'required',
        'actividad5' => 'required',
        'totalHoras' => 'required|string',
        'fechaEmision' => 'required|date',
        'archivo' => 'required|mimes:pdf|max:15360',
    ]);

    // Subir el archivo
    $archivo = $request->file('archivo');

    // Generar el nombre del archivo con el correo del usuario
    $nombreArchivo = Auth::user()->email . '_' . $archivo->getClientOriginalName();

    // Almacenar el archivo en el disco 'public' en la carpeta 'colegiaciones'
    $archivo->storeAs('IA01', $nombreArchivo, 'public');


    // Crear la entrada en la base de datos
    PPSIA01::create([
        'user_id' => auth()->user()->id,
        'carrera' => $request->carrera,
        'practica' => $request->practica,
        'dias' => $request->dias,
        'horario' => $request->horario,
        'fechaInicio' => $request->fechaInicio,
        'fechaFinalizacion' => $request->fechaFinalizacion,
        'modalidad' => $request->modalidad,
        'correo_vinculacion' => $request->correoVinculacion, 
        'actividad1' => $request->actividad1,
        'actividad2' => $request->actividad2,
        'actividad3' => $request->actividad3,
        'actividad4' => $request->actividad4,
        'actividad5' => $request->actividad5,
        'total_horas' => $request->totalHoras,
        'fechaEmision' => $request->fechaEmision,
        'estado_nota' => 'INGRESADA',
        'archivo' => $nombreArchivo,  
    ]);

    return redirect()->back()->with('success', 'Formulario guardado correctamente.');
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
