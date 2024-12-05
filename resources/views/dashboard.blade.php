@extends('adminlte::page')
<link rel="icon" href="{{ asset('images/Logo-unah.png') }}" type="image/x-icon">

@section('content_header')
    <h1>Estadísticas del sistema Practica Profesional Supervisada</h1>
@stop

@section('content')
    @if(isset($empresaPracticas) && $empresaPracticas->isEmpty())
        <div class="alert alert-warning">
            No hay datos disponibles para generar la gráfica.
        </div>
    @else
        <!-- Gráfico de barras de las Empresa Practicas -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Gráfica de barras de las 3 Empresas con más Alumnos</h3>
                <canvas id="empresaPracticaChart" style="max-height: 400px; max-width: 100%;"></canvas>
            </div>
        </div>
    @endif
@stop

@section('js')
    @if(isset($empresaPracticas) && $empresaPracticas->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        var ctx = document.getElementById('empresaPracticaChart').getContext('2d');

        // Asignar colores diferentes para las 3 empresas
        var colors = [
            'rgba(54, 162, 235, 0.6)', // Azul para la primera empresa
            'rgba(153, 102, 255, 0.6)', // Morado para la segunda empresa
            'rgba(255, 0, 0, 0.6)', // Rojo intenso para la tercera empresa
        ];

        // Recuperar los datos de las empresas y sus cantidades
        var empresas = @json($empresaPracticas->pluck('empresaPractica'));
        var cantidades = @json($empresaPracticas->pluck('count'));

        // Identificar la empresa con más alumnos
        var maxAlumnos = Math.max(...cantidades);
        var empresaConMas = empresas[cantidades.indexOf(maxAlumnos)];

        // Cambiar el título dinámicamente con la empresa con más alumnos
        document.querySelector('.card-title').innerHTML = `Gráfica de barras de la Empresa con más Alumnos: ${empresaConMas}`;

        var empresaPracticaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: empresas, // Nombres de las empresas
                datasets: [{
                    label: `Mayor Cantidad de Alumnos`,
                    data: cantidades, // Cantidades de cada empresa
                    backgroundColor: colors, // Colores diferentes para las barras
                    borderColor: colors.map(color => color.replace('0.6', '1')), // Cambio de opacidad para el borde
                    borderWidth: 2, // Aumento del grosor del borde
                    barThickness: 50, // Establece el grosor de las barras
                    hoverBackgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 0, 0, 0.8)'], // Colores al pasar el mouse
                    hoverBorderColor: ['rgba(54, 162, 235, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 0, 0, 1)'], // Borde más visible al pasar el mouse
                    animation: {
                        duration: 1500, // Duración de la animación (1.5 segundos)
                        easing: 'easeInOutQuad' // Tipo de animación
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Ajusta el tamaño para llenar el contenedor
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1, // Define los intervalos de la escala en el eje Y
                            font: {
                                size: 14, // Tamaño de la fuente para los números
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 14, // Tamaño de la fuente para las etiquetas en el eje X
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14, // Tamaño de la fuente de la leyenda
                            }
                        }
                    }
                }
            }
        });
        </script>
    @endif
@stop
