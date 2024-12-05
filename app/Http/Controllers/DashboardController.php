<?php

namespace App\Http\Controllers;

use App\Models\Colegiacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Obtener las estadísticas de las diferentes empresaPractica desde el modelo Colegiacion
    $empresaPracticas = Colegiacion::selectRaw('count(*) as count, empresaPractica')
        ->groupBy('empresaPractica')
        ->orderByDesc('count') // Ordenar de mayor a menor según la cantidad
        ->limit(3) // Solo obtener las 3 empresas con más alumnos
        ->get();

    // Pasar siempre $empresaPracticas, incluso si está vacío
    $message = $empresaPracticas->isEmpty() 
        ? 'No hay datos disponibles para mostrar las estadísticas.' 
        : null;

    return view('dashboard', compact('empresaPracticas', 'message'));
}


}
