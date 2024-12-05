<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="icon" href="{{ asset('images/Logo-unah.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Aplica la clase .show a los elementos para que se muestren con la animación
            document.getElementById("loginCard").classList.add("show");
            document.getElementById("welcomeMessage").classList.add("show");

            // Validación para evitar espacios y caracteres especiales en el email
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");

            if (emailInput) {
                emailInput.addEventListener("input", function() {
                    this.value = this.value
                        .replace(/[^a-zA-Z0-9@._%+-]/g, "") // Permite letras, números, @, ., %, +, -
                        .trim();
                });
            }

            if (passwordInput) {
                passwordInput.addEventListener("input", function() {
                    this.value = this.value.replace(/\s+/g, ""); // Elimina espacios en la contraseña
                });
            }
        });
</script>

</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="card login-card" id="loginCard">
                <br>
                <h2>Iniciar Sesión</h2>
                <h6>PRACTICA PROFESIONAL SUPERVISADA</h6>
                <br>
                
                <!-- Session Status (Solo para mostrar mensajes de sesión) -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Formulario de inicio de sesión -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Dirección de correo electrónico -->
                    <div class="form-group">
                        <label for="email">{{ __('Correo Institucional:') }}</label>
                        <input id="email" type="email" class="form-control" name="email" :value="old('email')" required autofocus
                            pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                            title="Introduce un correo válido. Solo se permite el uso de letras, números, '.', '@' y otros caracteres especiales como '_', '%', '+', y '-'."
                            autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group mt-4">
                        <label for="password">{{ __('Contraseña:') }}</label>
                        <input id="password" type="password" class="form-control" name="password" required
                               minlength="8" maxlength="50"
                               pattern="^[\S]+$"
                               title="La contraseña no puede contener espacios, y debe tener entre 8 y 50 caracteres."
                               autocomplete="current-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Recordar sesión -->
                    <div class="form-group form-check mt-4">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label" for="remember_me">{{ __('Recuérdame') }}</label>
                    </div>
                    
                    <!-- Botón de inicio de sesión y enlace de recuperación de contraseña -->
                    <div class="form-group d-flex justify-content-between mt-2">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Olvidaste tu contraseña?') }}
                            </a>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            {{ __('Iniciar') }}
                        </button>
                    </div>
                </form>

                <!-- Enlace de registro -->
                <div class="register-link">
                    <p>¿No tienes una cuenta?<a href="{{ route('register') }}" class="ml-2 text-sm text-gray-700 dark:text-gray-500 underline">Regístrate aquí</a></p>
                </div>
            </div>

            <!-- Mensaje de bienvenida -->
            <div class="welcome-message" id="welcomeMessage">
                <img src="{{ asset('images/Logo-unah.png') }}" alt="Application Logo" style="max-width: 100%; height: auto;">
                <h3>¡Bienvenido al sistema de Práctica Profesional Supervisada!</h3>
                <p>Estamos emocionados de que hayas llegado a esta etapa en tu educación. Felicitaciones por tu esfuerzo y dedicación. En la Universidad Nacional Autónoma de Honduras, valoramos tu compromiso con el aprendizaje y el desarrollo personal.</p>
                <p>Recuerda que cada paso que das te acerca más a tus metas. ¡Estamos aquí para apoyarte en tu camino hacia el éxito!</p>
            </div>
        </div>
    </div>
</body>
</html>
