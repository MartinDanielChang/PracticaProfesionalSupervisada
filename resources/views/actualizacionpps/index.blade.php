@extends('adminlte::page')

@section('title', 'PPS - Solicitud de Actualización de Datos')

@section('content_header')
    <h1 class="text-center">Solicitar Actualización de Datos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Formulario de Solicitud</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('actualizar-datos') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Tipo de solicitud -->
                <div class="form-group">
                    <label for="motivo">Motivo de la solicitud:</label>
                    <select name="tipoSolicitud" id="tipoSolicitud" class="form-control" required>
                        <option value="" disabled selected>Seleccine un motivo</option>
                        <option value="cambio_jefe">Cambio de Jefe Inmediato</option>
                        <option value="actualizacion_fecha">Actualización de Fecha</option>
                        <option value="incapacidad">Incorporación de Incapacidad</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <!-- Motivo de la solicitud -->
                <div class="form-group">
                    <label for="motivo">Descripción:</label>
                    <textarea name="motivo" id="motivo" class="form-control" rows="4" placeholder="Describa el motivo de la solicitud" required></textarea>
                </div>
                <div class="center-text">
                <i class="fas fa-file-pdf pdf-icon"></i> <!-- Icono PDF grande -->
                </div>
                <!-- Adjuntar archivo PDF -->
                <div class="form-group">
                    <label for="archivoPDF">Adjuntar Archivo (PDF)</label>
                    <input type="file" name="archivoPDF" id="archivoPDF" class="form-control-file" accept="application/pdf" required>
                </div>

                <!-- Botón de envío -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            margin-top: 20px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Formulario de solicitud de actualización de datos cargado.');
    </script>
@stop
