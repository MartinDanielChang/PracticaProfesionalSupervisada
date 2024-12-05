<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MantenimientoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Llama al procedimiento almacenado para obtener los usuarios
        $users = DB::select('CALL GetUsers()');
        
        // Retorna la vista con los usuarios
        return view('mantenimientousuario.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'numero' => 'required|string',
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email|max:255|unique:users,email',
        'no_identidad' => 'required|string|max:20',
        'password' => 'required|string|min:6',
        'cod_rol' => 'required|integer|exists:roles,COD_ROL',
    ]);

    try {
        // Hash de la contraseña
        $hashedPassword = Hash::make($request->password);

        // Llamada al procedimiento almacenado
        DB::select('CALL InsertUser(?, ?, ?, ?, ?, ?)', [
            $request->nombre,
            $request->correo,
            $request->numero,
            $request->no_identidad,
            $hashedPassword,
            $request->cod_rol,
        ]);

        // Redirección con mensaje de éxito
        return redirect()->route('mantenimientousuario.index')
                         ->with('success', 'Usuario creado exitosamente.');
    } catch (\Exception $e) {
        // Manejo de errores
        return redirect()->route('mantenimientousuario.index')
                         ->with('error', 'Ocurrió un error al crear el usuario: ' . $e->getMessage());
    }
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
        // Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:users,email,' . $id,
            'no_cuenta' => 'required|string|max:20',
            'no_identidad' => 'required|string|max:20',
            'COD_ROL' => 'required|integer|in:1,2,3',
        ]);

        // Llamada al procedimiento almacenado
        DB::select('CALL UpdateUser(?, ?, ?, ?, ?, ?)', [
            $id,
            $request->COD_ROL,
            $request->nombre,
            $request->correo,
            $request->no_cuenta,
            $request->no_identidad,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('mantenimientousuario.index')
                         ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Llamada al procedimiento almacenado para eliminar el usuario
        DB::select('CALL DeleteUser(?)', [$id]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('mantenimientousuario.index')
                         ->with('success', 'Usuario eliminado exitosamente.');
    }
}
