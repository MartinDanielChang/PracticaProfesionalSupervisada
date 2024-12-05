<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Permiso;

class CheckPermissions
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !session()->has('user_permisos')) {
            $user = Auth::user();

            // Cargar permisos del usuario basado en su rol
            $permisos = Permiso::where('COD_ROL', $user->COD_ROL)
                ->get()
                ->toArray();

            // Guardar permisos en la sesión de Laravel
            session(['user_permisos' => $permisos]);
        }

        return $next($request);
    }
}
