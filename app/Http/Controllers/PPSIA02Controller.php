<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPSIA02;
use App\Models\SolicitudPPS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PPSIA02Controller extends Controller
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

    public function subirIA02(Request $request)
{
    // Validación de los datos del formulario
    $validatedData = $request->validate([
        'horaInicioLunesViernes1' => 'required|date_format:H:i',
        'horaFinLunesViernes1' => 'required|date_format:H:i',
        'horaInicioLunesViernes2' => 'nullable|date_format:H:i',
        'horaFinLunesViernes2' => 'nullable|date_format:H:i',
        'horaInicioSabado' => 'nullable|date_format:H:i',
        'horaFinSabado' => 'nullable|date_format:H:i',
        'fechaInicio' => 'required|date',
        'fechaFinalizacion' => 'nullable|date',
        'puesto' => 'required|string|max:255',
        'departamento' => 'nullable|string|max:255',
        'fechaIngresoInstitucion' => 'required|date',
        'fechaInicioPuesto' => 'required|date',
        'jornadaLaboral' => 'required|string|in:Lunes a Viernes,Lunes a Sábado',
        'nombreInstitucion' => 'required|string|max:255',
        'direccionInstitucion' => 'required|string|max:255',
        'tipoInstitucion' => 'required|string|in:Publica,Privada',
        'fechaConstitucion' => 'nullable|date',
        'nombreJefe' => 'required|string|max:255',
        'cargoJefe' => 'required|string|max:255',
        'correoJefe' => 'required|email|max:255',
        'telefonoJefe' => 'nullable|string|max:15',
        'celularJefe' => 'required|string|max:15',
        'nivelAcademico' => 'required|string|in:Licenciatura,Maestria,Doctorado',
        'documentoPDF' => 'required|file|mimes:pdf|max:2048',
    ]);

    // Subir el archivo
    $nombreArchivo = null;
    if ($request->hasFile('documentoPDF')) {
        $archivo = $request->file('documentoPDF');
        $nombreArchivo = Auth::user()->email . '_' . $archivo->getClientOriginalName();
        $archivo->storeAs('IA02', $nombreArchivo, 'public');
    }

    // Obtener solicitud_id desde una lógica personalizada
    $solicitudId = SolicitudPPS::where('user_id', Auth::id())->latest()->value('id');

    if (!$solicitudId) {
        return redirect()->back()->withErrors(['error' => 'No se encontró una solicitud válida asociada al usuario.']);
    }

    // Crear registro en PPSIA02
    PPSIA02::create([
        'user_id' => Auth::id(),
        'solicitud_id' => $solicitudId,
        'hora_inicio_lunes_viernes1' => $validatedData['horaInicioLunesViernes1'],
        'hora_fin_lunes_viernes1' => $validatedData['horaFinLunesViernes1'],
        'hora_inicio_lunes_viernes2' => $validatedData['horaInicioLunesViernes2'],
        'hora_fin_lunes_viernes2' => $validatedData['horaFinLunesViernes2'],
        'hora_inicio_sabado' => $validatedData['horaInicioSabado'],
        'hora_fin_sabado' => $validatedData['horaFinSabado'],
        'puesto' => $validatedData['puesto'],
        'departamento' => $validatedData['departamento'],
        'fecha_ingreso_institucion' => $validatedData['fechaIngresoInstitucion'],
        'fecha_inicio_puesto' => $validatedData['fechaInicioPuesto'],
        'jornada_laboral' => $validatedData['jornadaLaboral'],
        'nombre_institucion' => $validatedData['nombreInstitucion'],
        'direccion_institucion' => $validatedData['direccionInstitucion'],
        'tipo_institucion' => $validatedData['tipoInstitucion'],
        'fecha_constitucion' => $validatedData['fechaConstitucion'],
        'nombre_jefe' => $validatedData['nombreJefe'],
        'cargo_jefe' => $validatedData['cargoJefe'],
        'correo_jefe' => $validatedData['correoJefe'],
        'telefono_jefe' => $validatedData['telefonoJefe'],
        'celular_jefe' => $validatedData['celularJefe'],
        'nivel_academico' => $validatedData['nivelAcademico'],
        'archivo' => $nombreArchivo,
        'estado' => 'INGRESADA',
    ]);

    // Retornar respuesta exitosa
    return redirect()->back()->with('success', 'Formulario enviado con éxito.');
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
