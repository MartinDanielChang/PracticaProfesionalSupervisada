<?php
// app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\Permiso;
use App\Models\Objetos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        // Cargar roles, permisos y objetos para la vista unificada
        $roles = Roles::all();
        $permisos = Permiso::with('role', 'objeto')->get();
        $objetos = Objetos::all();

        // Retornar la vista unificada
        return view('roles_permisos', compact('roles', 'permisos', 'objetos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NOM_ROL' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Roles::create([
            'NOM_ROL' => $request->NOM_ROL,
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'NOM_ROL' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Roles::findOrFail($id);
        $role->update([
            'NOM_ROL' => $request->NOM_ROL,
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Busca el rol por su ID
        $role = Roles::findOrFail($id);

        // Elimina el rol
        $role->delete();

        // Redirige de vuelta a la lista de roles con un mensaje de éxito
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
