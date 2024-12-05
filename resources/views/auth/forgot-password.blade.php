<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <link rel="icon" href="{{ asset('images/Logo-unah.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/olvidecontra.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
            <div class="card-body">
                <h2 class="card-title">Restablecimiento de Contraseña</h2>
                <img src="{{ asset('images/Logo-unah.png') }}" alt="Application Logo" style="max-width: 35%; height: 35%;">
                <br>
                <br>
                <br>
                <h6 class="card-subtitle mb-4">PRACTICA PROFESIONAL SUPERVISADA</h6>

                <div class="mb-4 text-sm text-gray-700 bg-gray-100 p-3">
                    {{ __('¿Olvidaste tu contraseña? No hay problema. Solo déjanos saber tu dirección de correo electrónico y te enviaremos un enlace para restablecer la contraseña que te permitirá elegir una nueva.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Enviar Enlace para Restablecer Contraseña') }}
                        </button>

                        <a class="btn btn-link" href="{{ route('login') }}">
                            {{ __('Volver al Inicio de Sesión') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
