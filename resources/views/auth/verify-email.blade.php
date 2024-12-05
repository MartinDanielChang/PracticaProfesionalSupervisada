<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo Electrónico</title>
    <link rel="icon" href="{{ asset('images/Logo-unah.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/olvidecontra.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
            <div class="card-body">
                <h2 class="card-title">Verificación de Correo Electrónico</h2>
                <img src="{{ asset('images/Logo-unah.png') }}" alt="Application Logo" style="max-width: 35%; height: 35%;">
                <br>
                <br>
                <h6 class="card-subtitle mb-4">PRACTICA PROFESIONAL SUPERVISADA</h6>

                <div class="mb-4 text-sm text-gray-700 bg-gray-100 p-4">
                    {{ __('¡Gracias por registrarte! Antes de comenzar, verifica tu dirección de correo electrónico haciendo clic en el enlace que te hemos enviado. Si no recibiste el correo, estaremos encantados de enviarte otro.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4">
                        {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.') }}
                    </div>
                @endif

                <div class="mt-4 d-flex justify-content-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div>
                            <x-primary-button class="btn btn-primary">
                                {{ __('Reenviar Correo de Verificación') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-link text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
