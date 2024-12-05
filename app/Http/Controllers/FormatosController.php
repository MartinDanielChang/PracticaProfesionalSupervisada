<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class FormatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rutaArchivos = base_path('storage/formatos');
            if (!is_dir($rutaArchivos)) {
            mkdir($rutaArchivos, 0777, true);
        }
        $archivos = array_diff(scandir($rutaArchivos), ['.', '..']);
        return view('formatos.index', compact('archivos'));
    }
    


    public function subirArchivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx',
        ]);

        // Obtén el archivo subido
        $archivo = $request->file('archivo');

        // Define el nombre original del archivo
        $nombreOriginal = $archivo->getClientOriginalName();

        // Guarda el archivo en la carpeta 'formatos' con el nombre original
        $archivo->storeAs('formatos', $nombreOriginal, 'public');

    return redirect()->back()->with('success', 'Archivo subido correctamente.');
    }

    public function eliminar($archivo)
    {
        // Elimina el archivo del almacenamiento público
        if (Storage::disk('public')->exists("formatos/$archivo")) {
            Storage::disk('public')->delete("formatos/$archivo");
            return redirect()->back()->with('success', 'Archivo eliminado correctamente.');
        }
        
        return redirect()->back()->with('error', 'El archivo no existe.');
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
