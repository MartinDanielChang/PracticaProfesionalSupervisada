@extends('adminlte::page')

@section('title', 'PPS-Modulo De ')

@section('content_header')

@stop

@section('content')
<div class="content">
    <br>
    <h2 class="text-center">Solicitud de Cancelación de Práctica</h2>
    <p>Por favor, completa el siguiente formulario para solicitar la cancelación de tu práctica. Asegúrate de proporcionar toda la información requerida.</p>

    <form action="/PPS/procesar_cancelacion.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo Institucional:</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="form-group">
            <label for="motivo">Motivo de la Cancelación:</label>
            <textarea class="form-control" id="motivo" name="motivo" rows="4" required></textarea>
        </div>
        <div class="center-text">
        <i class="fas fa-file-pdf pdf-icon"></i> <!-- Icono PDF grande -->
        </div>
        
        <div class="form-group">
            <label for="documento">Adjuntar Documentación:</label>
            <input type="file" class="form-control-file" id="documento" name="documento">
        </div>
        <button type="submit" class="btn btn-danger">Enviar Solicitud</button>
    </form>

    <h3 class="text-center" class="mt-4">Información</h3>
    <ul style="list-style-type: none; padding: 0;">
        <li><span style="font-weight: bold; font-size: 1.5em;">·</span> Recuerda que tu solicitud será revisada y se te notificará sobre su estado.</li>
        <li><span style="font-weight: bold; font-size: 1.5em;">·</span> El proceso de cancelación puede tomar hasta 5 días hábiles.</li>
        <li><span style="font-weight: bold; font-size: 1.5em;">·</span> Asegúrate de adjuntar cualquier documento que respalde tu solicitud.</li>
    </ul>
</div>
@stop

@section('css')

@stop

@section('js')

@stop