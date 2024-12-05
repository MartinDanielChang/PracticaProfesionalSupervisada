@extends('adminlte::page')

@section('title', 'PPS-Modulo De ')

@section('content_header')

@stop

@section('content')
<br>
<div class="container my-5">
    <!-- Tarjeta de Respaldo -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Punto de Backup</h3>
            <i class="bi bi-cloud-arrow-down-fill" style="font-size: 1.5rem;"></i>
        </div>
        <div class="card-body text-center">
            <p class="mb-4">Haz clic en el botón de respaldo para crear una copia de seguridad de la base de datos.</p>
            <form id="backupForm" action="{{ route('respaldo.backup') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-lg btn-primary px-5">Crear Respaldo</button>
            </form>
        </div>
    </div>

    <!-- Tarjeta de Restauración -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Punto de Restauración</h3>
            <i class="bi bi-cloud-arrow-up-fill" style="font-size: 1.5rem;"></i>
        </div>
        <div class="card-body">
    <form id="restoreForm" action="{{ route('respaldo.restore') }}" method="post">
        @csrf
        <div class="form-group mb-4">
            <label for="restorePoint" class="form-label fw-semibold">Selecciona un punto de restauración</label>
            <select name="restorePoint" id="restorePoint" class="form-select form-select-lg mb-3">
                <option value="" selected disabled>Selecciona un punto de restauración</option>
                @foreach($backups as $backup)
                    <option value="{{ $backup }}">{{ $backup }}</option>
                @endforeach
            </select>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-warning px-5">Restaurar</button>
        </div>
    </form>
</div>
    </div>
</div>

@stop

@section('css')

@stop

@section('js')
<script>
    // Obtén el formulario y el botón
    const form = document.getElementById('backupForm');
    const button = form.querySelector('button[type="submit"]');
    
    // Escucha el evento de envío del formulario
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío por defecto
        
        // Puedes realizar cualquier otra lógica aquí si es necesario
        
        // Enviar el formulario usando fetch para luego recargar la página
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Asegúrate de tener la etiqueta meta de CSRF
            }
        })
        .then(response => {
            if (response.ok) {
                // Recargar la página después de un respaldo exitoso
                window.location.reload();
            } else {
                alert('Error al crear el respaldo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema con el respaldo.');
        });
    });
</script>
<script>
    // Obtén el formulario y el botón
    const restoreForm = document.getElementById('restoreForm');
    
    // Escucha el evento de envío del formulario
    restoreForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío por defecto
        
        // Verifica que se haya seleccionado un punto de restauración
        const restorePoint = document.getElementById('restorePoint').value;
        if (!restorePoint) {
            alert('Por favor selecciona un punto de restauración.');
            return;
        }

        // Enviar el formulario usando fetch para luego recargar la página
        fetch(restoreForm.action, {
            method: 'POST',
            body: new FormData(restoreForm),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Asegúrate de tener la etiqueta meta de CSRF
            }
        })
        .then(response => {
            if (response.ok) {
                // Recargar la página después de una restauración exitosa
                window.location.reload();
            } else {
                alert('Error al restaurar el respaldo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema con la restauración.');
        });
    });
</script>
@stop