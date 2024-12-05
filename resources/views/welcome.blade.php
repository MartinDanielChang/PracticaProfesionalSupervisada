<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica Profesional</title>

    <!-- Icono de la Página -->
    <link rel="icon" href="{{ asset('images/logo-unah.png') }}" type="image/x-icon">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav a {
            text-decoration: none;
            color: #ffffff;
            background-color: #2980b9;
            padding: 12px 24px;
            margin-left: 10px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .nav a:hover {
            background-color: #1c5980;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .content {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: justify;
        }

        .highlight {
            color: #2980b9;
            font-weight: bold;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Encabezado con Logo y Botones de Acceso -->
        <div class="header">
            <img src="{{ asset('images/logo-unah.png') }}" alt="Logo UNAH" style="height: 100px;">

            <!-- Botones de Login y Registro -->
            <div class="nav">
                <a href="/login">Acceder a mi Cuenta</a>
                <a href="/register">Crear Nueva Cuenta</a>
            </div>
        </div>

        <h1>Práctica Profesional</h1>
        
        <div class="content">
            <p>
                La <span class="highlight">práctica profesional</span> es una etapa fundamental en la formación de cualquier profesional. Es un espacio de aprendizaje donde se aplica y se fortalece el conocimiento adquirido en el ámbito académico, facilitando el desarrollo de habilidades técnicas y personales que serán esenciales en el mundo laboral.
            </p>

            <p>
                En esta etapa, los estudiantes y nuevos graduados tienen la oportunidad de conocer el funcionamiento real de las organizaciones, enfrentarse a desafíos reales, y adquirir experiencia bajo la supervisión de profesionales experimentados. La práctica profesional permite, además, la creación de redes de contacto y puede ser un trampolín para obtener oportunidades laborales futuras.
            </p>
        </div>
        
        <footer>
            &copy; 2024 - Práctica Profesional
        </footer>
    </div>

</body>
</html>
