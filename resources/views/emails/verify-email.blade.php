<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo Electrónico</title>
    <style>
        .email-container {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
        }
        .content p {
            font-size: 14px;
            color: #333333;
        }
        .content span {
            font-weight: bold;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .button {
            display: block;
            width: 220px;
            margin: 10px auto;
            padding: 12px 20px;
            text-align: center;
            background-color: #FFD700;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .button:hover {
            background-color: #E6C200;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="content">
            <div class="logo">
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhdN5bsVpDN81WuqdqpPxbJbTM3YBVzFnHBFUsFmG7t4cYXUj-FAuAXaoG0nIG91S9b-aR6ViCLWVidfY2diBUDeE3ByYCv0rgvHxqp3Ft8gHIa2MhFg-SkdCLpPXorDTM9_dUUUE6ge-1j/s1600/SetWidth970-unah.jpg" alt="Logo" width="150" height="auto">
            </div>
            <p>Hola <span><strong>{{ $user->name }}</strong></span>,</p>
            <p>¡Bienvenido! Nos alegra que estés aquí. Por favor, verifica tu dirección de correo electrónico haciendo clic en el botón de abajo.</p>
            <p>Si tienes alguna pregunta, no dudes en ponerte en contacto con <a href="mailto:adminpps.dia@unah.edu.hn">adminpps.dia@unah.edu.hn</a> o con nuestro equipo de soporte.</p>
            <a href="{{ $url }}" class="button">Verificar Correo Electrónico</a>
            <p>¡Gracias!</p>
            <br>
            <p>Si no has solicitado esta acción, puedes ignorar este mensaje.</p>
        </div>
    </div>
</body>
</html>
