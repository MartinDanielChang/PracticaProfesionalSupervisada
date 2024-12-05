<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="icon" href="{{ asset('images/Logo-unah.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const nameInput = document.getElementById("name");  // Asegúrate de que esta variable esté definida
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("password_confirmation");
        const form = document.querySelector("form");

        // Validación en tiempo real para el nombre: solo mayúsculas y permite un espacio entre palabras
        nameInput.addEventListener("input", function() {
            this.value = this.value
                .toUpperCase()  // Convierte a mayúsculas
                .replace(/[^A-ZÁÉÍÓÚÑ\s]/g, "")  // Permite solo letras en mayúsculas y espacios
                .replace(/\s{2,}/g, " ");  // Reemplaza múltiples espacios consecutivos con uno solo
        });

        // Validación en tiempo real para evitar caracteres especiales en el correo
        emailInput.addEventListener("input", function() {
            this.value = this.value
                .trim()
                .replace(/[^a-zA-Z0-9@._-]/g, "");  // Permite solo letras, números, @, ., _ y -
        });

        // Validación en tiempo real para la contraseña (sin espacios en blanco)
        passwordInput.addEventListener("input", function() {
            this.value = this.value.replace(/\s/g, "");  // Elimina espacios en blanco
        });

        // Validación en tiempo real para la confirmación de contraseña (sin espacios en blanco)
        confirmPasswordInput.addEventListener("input", function() {
            this.value = this.value.replace(/\s/g, "");  // Elimina espacios en blanco
        });

        // Validación para confirmar que las contraseñas coinciden y contienen un carácter especial
        form.addEventListener("submit", function(event) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                alert("Las contraseñas no coinciden.");
                event.preventDefault();  // Evita el envío si las contraseñas no coinciden
            } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordInput.value)) {
                alert("La contraseña debe contener al menos un carácter especial.");
                event.preventDefault();  // Evita el envío si no contiene un carácter especial
            }
        });
    });
</script>

</head>
<body>
    <div class="container">
        <div class="register-box">
        <img src="{{ asset('images/Logo-unah.png') }}" alt="Application Logo" style="max-width: 35%; height: 35%; display: block; margin: auto;">
        <br>    
        <h2>Registro de Usuario</h2>
            <p class="instructions">
                Por favor, completa el formulario. Asegúrate de que la información proporcionada sea correcta.
            </p>

            <!-- Formulario de Registro adaptado -->
            <form action="{{ route('register') }}" method="POST">
                @csrf <!-- Token CSRF necesario para Laravel -->

                <!-- Nombre -->
                <div class="input-group">
                    <label for="name">Nombre Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        minlength="3" maxlength="50"
                        title="El nombre debe estar en mayúsculas. Puedes incluir espacios entre palabras."
                        autocomplete="name" oninput="this.value = this.value.toUpperCase()">
                    @if ($errors->has('name'))
                        <p class="error">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <!-- Correo Personal -->
                <div class="input-group">
                    <label for="email">Correo Institucional</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                           title="Introduce un correo válido sin espacios ni caracteres especiales extraños."
                           autocomplete="username">
                    @if ($errors->has('email'))
                        <p class="error">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Contraseña -->
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required minlength="8" maxlength="50"
                           title="La contraseña debe contener al menos un carácter especial y no debe tener espacios."
                           autocomplete="new-password">
                    @if ($errors->has('password'))
                        <p class="error">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Confirmar Contraseña -->
                <div class="input-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           minlength="8" maxlength="50"
                           title="La confirmación debe coincidir con la contraseña y no contener espacios."
                           autocomplete="new-password">
                    @if ($errors->has('password_confirmation'))
                        <p class="error">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <!-- Botón de Registro -->
                <button type="submit">Registrarse</button>

                <!-- Enlace a Iniciar Sesión -->
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
            </form>
        </div>
    </div>
</body>
</html>
