<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use App\Models\Permiso;

class LoadUserPermissions
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cacheKey = 'user_permissions_' . $user->id;

            // Intenta obtener los permisos del cache
            $permisos = Cache::remember($cacheKey, 60, function () use ($user) {
                return Permiso::where('COD_ROL', $user->COD_ROL)->get();
            });

            // Guarda los permisos en la sesión
            Session::put('user_permisos', $permisos);
        }

        return $next($request);
    }
}
