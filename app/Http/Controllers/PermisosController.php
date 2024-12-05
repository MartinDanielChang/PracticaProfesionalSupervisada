<?php
namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Roles;
use App\Models\Objetos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class PermisosController extends Controller
{
    public function index()
    {
        $roles = Roles::all(); // Obtener todos los roles
        $objetos = Objetos::all(); // Obtener todos los objetos
        $permisos = Permiso::with(['role', 'objeto'])->get(); // Obtener permisos con relaciones
    
        // Pasar las variables a la vista
        return view('roles_permisos', compact('roles', 'objetos', 'permisos'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'COD_ROL' => 'required|exists:roles,COD_ROL',
            'COD_OBJETO' => 'required|exists:objetos,COD_OBJETO',
        ]);

        $data = $request->all();
        $data['IND_MODULO'] = $request->has('IND_MODULO') ? '1' : '0';
        $data['IND_SELECT'] = $request->has('IND_SELECT') ? '1' : '0';
        $data['IND_INSERT'] = $request->has('IND_INSERT') ? '1' : '0';
        $data['IND_UPDATE'] = $request->has('IND_UPDATE') ? '1' : '0';
        $data['USR_ADD'] = Auth::user()->name;
        
        Permiso::create($data);

        return redirect()->route('roles_permisos.index')->with('success', 'Permiso creado exitosamente.');
    }

    public function update(Request $request, Permiso $permiso)
    {
        $request->validate([
            'COD_ROL' => 'required|exists:roles,COD_ROL',
            'COD_OBJETO' => 'required|exists:objetos,COD_OBJETO',
        ]);
    
        $data = $request->all();
        $data['IND_MODULO'] = $request->has('IND_MODULO') ? '1' : '0';
        $data['IND_SELECT'] = $request->has('IND_SELECT') ? '1' : '0';
        $data['IND_INSERT'] = $request->has('IND_INSERT') ? '1' : '0';
        $data['IND_UPDATE'] = $request->has('IND_UPDATE') ? '1' : '0';
        $data['USR_ADD'] = Auth::user()->name;
    
        // Si `$roleId` viene en el request
        $roleId = $request->input('COD_ROL'); // Puedes usarlo aquí si es necesario
    
        $permiso->update($data);
    
        return redirect()->route('roles_permisos.index')->with('success', 'Permiso actualizado exitosamente.');
    }
    

    public function destroy(Permiso $permiso)
    {
        $permiso->delete();
        return redirect()->route('roles_permisos.index')->with('success', 'Permiso eliminado exitosamente.');
    }
}

