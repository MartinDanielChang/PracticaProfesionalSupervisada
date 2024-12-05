<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Ignora las rutas predeterminadas de Fortify
        Fortify::ignoreRoutes();

        // Define aquí tus configuraciones o callbacks personalizados para Fortify
        // Ejemplo: Configurar autenticación personalizada
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }
}
