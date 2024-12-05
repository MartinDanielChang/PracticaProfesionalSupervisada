<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use App\Models\Permiso;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Maneja la autenticación de un usuario.
     */
    public function store(Request $request)
    {
        // Validar las credenciales ingresadas
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            // Regenerar la sesión de Laravel
            $request->session()->regenerate();

            // Llamar al método authenticated para manejar la carga de permisos
            return $this->authenticated($request, Auth::user());
        }

        // Si falla la autenticación, redirigir con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

      /**
     * Maneja la autenticación después de un login exitoso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        // Regenerar la sesión de Laravel
        $request->session()->regenerate();
    
        // Iniciar sesión PHP nativa si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Cargar permisos desde la base de datos
        $permisos = Permiso::where('COD_ROL', $user->COD_ROL)
                    ->get(['COD_OBJETO', 'IND_MODULO', 'IND_SELECT', 'IND_INSERT', 'IND_UPDATE'])
                    ->toArray();
    
        // Guardar permisos en sesión Laravel y PHP nativa
        session(['user_permisos' => $permisos]);
        $_SESSION['user_permisos'] = $permisos;
    
        return redirect('/dashboard');
    }
    


      // Esta es la función attemptLogin donde intenta autenticar al usuario
      protected function attemptLogin(Request $request)
      {
          return $this->guard()->attempt(
              $this->credentials($request), $request->boolean('remember')
          );
      }
    /**
     * Maneja la solicitud de cierre de sesión.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // Limpiar sesiones Laravel
        session()->forget(['user_permisos', 'user_roles']);
        Auth::logout();
    
        // Invalidar la sesión de Laravel
        $request->session()->invalidate();
    
        // Regenerar el token de la sesión
        $request->session()->regenerateToken();
    
        // Destruir sesión PHP nativa si está activa
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }
    
        return redirect('/');
    }
    

    
    
}
