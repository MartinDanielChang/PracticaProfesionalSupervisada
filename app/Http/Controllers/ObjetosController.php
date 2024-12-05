<?php

namespace App\Http\Controllers;

use App\Models\Objetos;
use Illuminate\Http\Request;

class ObjetosController extends Controller
{
    public function index()
    {
        $objetos = Objetos::all();
        return view('objetos.index', compact('objetos'));
    }

    public function create()
    {
        return view('objetos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOM_OBJETO' => 'required|string|max:255',
            'TIP_OBJETO' => 'required|string|max:255',
            'DES_OBJETO' => 'nullable|string|max:255',
            'IND_OBJETO' => 'required|in:1,0',
        ]);

        Objetos::create([
            'NOM_OBJETO' => $request->NOM_OBJETO,
            'TIP_OBJETO' => $request->TIP_OBJETO,
            'DES_OBJETO' => $request->DES_OBJETO,
            'IND_OBJETO' => $request->IND_OBJETO,
            'USR_ADD' => auth()->user()->name, // Asumiendo que tienes autenticación y quieres registrar el usuario que creó el objeto
        ]);

        return redirect()->route('objetos.index')->with('success', 'Objeto creado exitosamente.');
    }

    public function show(Objetos $objeto)
    {
        return view('objetos.show', compact('objeto'));
    }

    public function edit(Objetos $objeto)
    {
        return view('objetos.edit', compact('objeto'));
    }

    public function update(Request $request, Objetos $objeto)
    {
        $request->validate([
            'NOM_OBJETO' => 'required|string|max:255',
            'TIP_OBJETO' => 'required|string|max:255',
            'DES_OBJETO' => 'nullable|string|max:255',
            'IND_OBJETO' => 'required|in:1,0',
        ]);

        $objeto->update([
            'NOM_OBJETO' => $request->NOM_OBJETO,
            'TIP_OBJETO' => $request->TIP_OBJETO,
            'DES_OBJETO' => $request->DES_OBJETO,
            'IND_OBJETO' => $request->IND_OBJETO,
            'USR_ADD' => auth()->user()->name, // Asumiendo que quieres actualizar el usuario que modifica el objeto
        ]);

        return redirect()->route('objetos.index')->with('success', 'Objeto actualizado exitosamente.');
    }

    public function destroy(Objetos $objeto)
    {
        $objeto->delete();
        return redirect()->route('objetos.index')->with('success', 'Objeto eliminado exitosamente.');
    }
}
