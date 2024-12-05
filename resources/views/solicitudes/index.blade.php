@extends('adminlte::page')

@section('title', 'PPS-Modulo De Solicitudes a PPS')

@section('content_header')
    <h1>Solicitudes a PPS</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Pestañas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="solicitudes-tab" data-bs-toggle="tab" href="#solicitudes" role="tab" aria-controls="solicitudes" aria-selected="true">Solicitudes</a>
        </li>
        @if($estado_solicitud->contains('SOLICITADA'))
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="colegiacion-tab" data-bs-toggle="tab" href="#colegiacion" role="tab" aria-controls="colegiacion" aria-selected="false">Colegiación</a>
        </li>
        @endif
        @if($estado_nota_colegiacion->contains('INGRESADA') && $tipo_practica === 'Normal')
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="carta-tab" data-bs-toggle="tab" href="#carta" role="tab" aria-controls="carta" aria-selected="false">Constancia de Presentación</a>
        </li>
        @endif
        @if($estado_nota_presentacion->contains('INGRESADA') && $tipo_practica === 'Normal')
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="formato-tab" data-bs-toggle="tab" href="#formato" role="tab" aria-controls="formato" aria-selected="false">Formato IA-01</a>
        </li>
        @endif
        @if($estado_nota_ppsia01->contains('INGRESADA') && $tipo_practica === 'Normal')
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="constancia-tab" data-bs-toggle="tab" href="#constancia" role="tab" aria-controls="constancia" aria-selected="false">Constancia de Aceptación</a>
            </li>
        @endif
        @if($estado_nota_aceptacion->contains('INGRESADA') && $tipo_practica === 'Normal')
            <li class="nav-item" role="presentation">
                <a class="nav-link finalizacion" id="finalizacion-tab" data-bs-toggle="tab" href="#finalizacion" role="tab" aria-controls="finalizacion" aria-selected="false">Constancia de Finalización</a>
            </li>
        @endif
    </ul>

    <div class="tab-content" id="myTabContent">
       <!-- Pestaña Solicitudes -->
<div class="tab-pane fade show active" id="solicitudes" role="tabpanel" aria-labelledby="solicitudes-tab">
    <div class="container mt-4">
        <h3 class="text-dark mb-4">Solicitudes</h3>
        <!-- Botón para iniciar solicitud si no existe -->
        @if(!$estado_solicitud->contains('SOLICITADA'))
        <button type="button" id="iniciarSolicitudBtn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#iniciarSolicitudModal">
            Iniciar Solicitud
        </button>
        @endif
        <!-- Tabla de Solicitudes -->
        <div id="solicitudesContainer">
            <table class="table table-bordered table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Estado Solicitud</th>
                        <th>Fecha Solicitud</th>
                        <th>Descripción</th>
                        <th>Tipo Práctica</th>
                        <th>Supervisor Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->estado_solicitud }}</td>
                        <td>{{ $solicitud->fecha_solicitud }}</td>
                        <td>{{ $solicitud->descripcion }}</td>
                        <td>{{ $solicitud->tipo_practica }}</td>
                        <td>
                                    @if($solicitud->estado_solicitud === 'APROBADO' && $solicitud->supervisor)
                                        <span class="text-success">El supervisor asignado es: <strong>{{ $solicitud->supervisor->nombre }}</strong></span>
                                    @else
                                        <span class="text-muted">Información no disponible</span>
                                    @endif
                                </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Formatos disponibles para PPS -->
        <div class="mt-5">
            <h2 class="text-center">Formatos Disponibles para PPS</h2>
            <table id="tableId" class="table table-striped table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Documentos</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (Storage::files('public/formatos') as $archivo)
                    <tr>
                        <td>{{ basename($archivo) }}</td>
                        <td><a href="{{ Storage::url($archivo) }}" download class="btn btn-link">Descargar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pestaña Colegiación -->
<div class="tab-pane fade" id="colegiacion" role="tabpanel" aria-labelledby="colegiacion-tab">
    <div class="container mt-4">
        <h3 class="text-dark mb-4">Colegiación</h3>
        <h5 class="text-secondary mb-4">Adjuntar Colegiación y Datos</h5>
        <!-- Formulario de Colegiación -->
        <form id="colegiacionForm" action="{{ route('subirArchivo') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label for="archivo" class="form-label fw-bold">Adjuntar archivo PDF:</label>
        <input type="file" id="archivo" name="archivo" accept=".pdf" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="preinscripcion" class="form-label">Número de Preinscripción</label>
        <input type="text" class="form-control" id="preinscripcion" name="preinscripcion" required>
    </div>

    <div class="mb-3">
        <label for="empresaPractica" class="form-label">Empresa para la práctica profesional</label>
        <input type="text" class="form-control" id="empresaPractica" name="empresaPractica">
    </div>

    <div class="mb-3">
        <label class="form-label">Actualmente labora:</label>
        <div>
            <label><input type="radio" id="laboraSi" name="labora" value="Si"> Sí</label>
            <label class="ms-3"><input type="radio" id="laboraNo" name="labora" value="No" checked> No</label>
        </div>
    </div>

    <div id="trabajoFields" style="display: none;">
        <div class="mb-3">
            <label for="unidadDepartamento" class="form-label">Unidad o Departamento</label>
            <input type="text" class="form-control" id="unidadDepartamento" name="unidadDepartamento">
        </div>
        <div class="mb-3">
            <label for="direccionExacta" class="form-label">Dirección Exacta</label>
            <input type="text" class="form-control" id="direccionExacta" name="direccionExacta">
        </div>
        <div class="row">
            <div class="col-md-4">
                <label for="celularEmpresa" class="form-label">Celular</label>
                <input type="tel" class="form-control" id="celularEmpresa" name="celularEmpresa">
            </div>
            <div class="col-md-4">
                <label for="telefonoFijo" class="form-label">Teléfono Fijo</label>
                <input type="tel" class="form-control" id="telefonoFijo" name="telefonoFijo">
            </div>
            <div class="col-md-4">
                <label for="extension" class="form-label">Extensión</label>
                <input type="text" class="form-control" id="extension" name="extension">
            </div>
        </div>
        <div class="mb-3">
            <label for="correoEmpresa" class="form-label">Correo de la Empresa</label>
            <input type="email" class="form-control" id="correoEmpresa" name="correoEmpresa">
        </div>
        <div class="mb-3">
            <label for="cargo" class="form-label">Cargo Actual</label>
            <input type="text" class="form-control" id="cargo" name="cargo">
        </div>
        <div class="mb-3">
            <label for="fechaIngreso" class="form-label">Fecha de Ingreso</label>
            <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso">
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-3">Enviar</button>
</form>

        <!-- Confirmación -->
        <div id="confirmacionColegiacion" class="mt-5 text-center d-none">
            <i class="fas fa-file-pdf text-danger" style="font-size: 50px;"></i>
            <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo Sistema" class="img-fluid" style="width: 50px;">
            <h2 class="text-primary">¡Colegiación Recibida!</h2>
            <p>Tu colegiación ha sido recibida exitosamente. Nuestro equipo la revisará pronto.</p>
        </div>
    </div>
</div>



<!-- Pestaña Carta Presentación -->
<div class="tab-pane fade" id="carta" role="tabpanel" aria-labelledby="carta-tab">
    <br>
    <h3 style="font-size: 1.5em; color: #333; margin-bottom: 10px;">Constancia Presentación</h3>
    <br>
    <h2 style="font-size: 1.2em; color: #666; margin-bottom: 20px;">Adjuntar Carta de Presentación</h2>

    <!-- Formulario para cargar archivo PDF -->
    <form id="presentacionForm" action="{{ route('subirPresentacion') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="archivoPresentacion" style="display: block; margin-bottom: 10px; color: #444; font-weight: bold;">
            Adjuntar archivo PDF:
        </label>
        <input type="file" id="archivoPresentacion" name="archivo" accept=".pdf" required
            style="display: block; width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9em; cursor: pointer; margin-bottom: 20px;">
        <button type="submit" style="background-color: #5cb85c; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 1em;">
            Enviar
        </button>
    </form>

    <!-- Mensaje de éxito -->
    @if(session('success'))
    <div id="confirmacionConstancia" style="text-align: center; margin-top: 30px; color: #444;">
        <i class="fas fa-file-alt" style="font-size: 50px; color: #27ae60; margin-right: 10px;"></i>
        <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo Sistema" style="width: 50px;">
        <h2 style="color: #4a90e2;">¡Constancia de Presentación Recibida!</h2>
        <p>{{ session('success') }}</p>
    </div>
    @endif

            <!-- Mensaje de Confirmación para Constancia de Presentación -->
        <div id="confirmacionConstancia" style="display: none; text-align: center; margin-top: 30px; color: #444;">
            <!-- Icono de Documento desde Font Awesome -->
            <i class="fas fa-file-alt" style="font-size: 50px; color: #27ae60; margin-right: 10px;"></i>
            <!-- Logo del sistema -->
            <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo Sistema" style="width: 50px;">
            <h2 style="color: #4a90e2;">¡Constancia de Presentación Recibida!</h2>
            <p>Tu constancia de presentación ha sido recibida exitosamente. Nuestro equipo la revisará pronto.</p>
        </div>
    
    </div>

    <div class="tab-pane fade" id="formato" role="tabpanel" aria-labelledby="formato-tab">
    <h3 class="text-center my-4">Formulario IA-01</h3>
    <form id="IAForm" action="{{ route('subirIA01') }}" method="POST" enctype="multipart/form-data" class="container p-4 shadow rounded bg-light">
        @csrf

        <!-- Información de Carrera -->
        <div class="mb-3">
            <label for="carrera" class="form-label">Carrera</label>
            <input type="text" class="form-control" id="carrera" name="carrera" value="Informática Administrativa" readonly>
        </div>

        <!-- Información de Práctica Profesional Supervisada -->
        <div class="mb-3">
            <label for="practica" class="form-label">Práctica Profesional Supervisada (PPS)</label>
            <input type="text" class="form-control" id="practica" name="practica" value="800 horas" readonly>
        </div>

        <!-- Selección de Días -->
        <div class="mb-3">
            <label for="dias" class="form-label">Días</label>
            <select class="form-select" id="dias" name="dias" required>
                <option value="lunes-viernes">Lunes a Viernes</option>
                <option value="lunes-sabado">Lunes a Sábado</option>
                <option value="lunes-domingo">Lunes a Domingo</option>
            </select>
        </div>

        <!-- Horarios Dinámicos -->
        <div class="row mb-3">
            <div class="col">
                <label for="horaInicio" class="form-label">Hora de Inicio</label>
                <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
            </div>
            <div class="col">
                <label for="horaFin" class="form-label">Hora de Finalización</label>
                <input type="time" class="form-control" id="horaFin" name="horaFin" required>
            </div>
        </div>

        <!-- Fechas -->
        <div class="row mb-3">
            <div class="col">
                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
            </div>
            <div class="col">
                <label for="fechaFinalizacion" class="form-label">Fecha de Finalización</label>
                <input type="date" class="form-control" id="fechaFinalizacion" name="fechaFinalizacion" required>
            </div>
        </div>

        <!-- Modalidad -->
        <div class="mb-3">
            <label for="modalidad" class="form-label">Modalidad</label>
            <select class="form-select" id="modalidad" name="modalidad" required>
                <option value="presencial">Presencial</option>
                <option value="teletrabajo">Teletrabajo</option>
                <option value="hibrida">Híbrida</option>
            </select>
        </div>

        <!-- Correo de Vinculación -->
        <div class="mb-3">
            <label for="correoVinculacion" class="form-label">Correo de Vinculación</label>
            <input type="email" class="form-control" id="correoVinculacion" name="correoVinculacion" value="pps.dia@unah.edu.hn" readonly>
        </div>

        <!-- Actividades -->
        <div class="mb-3">
            <label for="actividades" class="form-label">Actividades</label>
            <div class="row g-2">
                <div class="col-md">
                    <input type="text" class="form-control" id="actividad1" name="actividad1" placeholder="Actividad 1">
                </div>
                <div class="col-md">
                    <input type="text" class="form-control" id="actividad2" name="actividad2" placeholder="Actividad 2">
                </div>
                <div class="col-md">
                    <input type="text" class="form-control" id="actividad3" name="actividad3" placeholder="Actividad 3">
                </div>
                <div class="col-md">
                    <input type="text" class="form-control" id="actividad4" name="actividad4" placeholder="Actividad 4">
                </div>
                <div class="col-md">
                    <input type="text" class="form-control" id="actividad5" name="actividad5" placeholder="Actividad 5">
                </div>
            </div>
        </div>

        <!-- Total de Horas -->
        <div class="mb-3">
            <label for="totalHoras" class="form-label">Total Horas</label>
            <input type="text" class="form-control" id="totalHoras" name="totalHoras" value="800" readonly>
        </div>

        <!-- Ciudad -->
        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
        </div>

        <!-- Fecha de Emisión -->
        <div class="mb-3">
            <label for="fechaEmision" class="form-label">Fecha de Emisión</label>
            <input type="date" class="form-control" id="fechaEmision" name="fechaEmision" required>
        </div>

        <!-- Nombre del Jefe -->
        <div class="mb-3">
            <label for="jefe" class="form-label">Nombre del Jefe Inmediato Superior o Director de RRHH</label>
            <input type="text" class="form-control" id="jefe" name="jefe" required>
        </div>

        <!-- Celular -->
        <div class="mb-3">
            <label for="celular" class="form-label">Celular</label>
            <input type="tel" class="form-control" id="celular" name="celular" required>
        </div>

        <!-- Archivo PDF -->
        <div class="mb-3">
            <label for="archivo" class="form-label">Adjuntar Archivo (PDF)</label>
            <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf" required>
        </div>

        <!-- Botón para enviar -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
        </div>
    </form>

    <!-- Confirmación IA-01 -->
<div id="confirmacionIA01" class="mt-5 text-center d-none">
    <i class="fas fa-file-alt text-primary" style="font-size: 50px;"></i>
    <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo UNAH" class="img-fluid my-3" style="width: 60px;">
    <h2 class="text-success">¡Formato IA-01 Recibida!</h2>
    <p>Tu documentación ha sido recibida exitosamente. Nuestro equipo la revisará y te notificará pronto.</p>
</div>

</div>


        <!-- Pestaña Constancia Aceptación -->
        <div class="tab-pane fade" id="constancia" role="tabpanel" aria-labelledby="constancia-tab">
            <br>
            <h3>Constancia de Aceptación</h3>
            <br>
            <h2 style="font-size: 1.2em; color: #666; margin-bottom: 20px;">Adjuntar Carta de Aceptación</h2>
            <!-- Formulario para cargar el PDF -->
            <form id="aceptacionForm" action="{{ route('subirAceptacion') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="pdf_file" class="font-weight-bold">Selecciona un archivo PDF:</label>
                    <input type="file" name="archivo" id="pdf_file" class="form-control" accept=".pdf" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mt-3">Enviar</button>
                </div>
            </form>

           <!-- Confirmación -->
            <div id="confirmacionAceptacion" class="mt-5 text-center d-none">
                <!-- Icono de documento dorado -->
                <i class="fas fa-file-alt" style="font-size: 50px; color: gold;"></i>
                <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo Sistema" class="img-fluid" style="width: 50px;">
                <h2 class="text-primary">¡Constancia Recibida!</h2>
                <p>Tu constancia ha sido recibida exitosamente. Nuestro equipo la revisará pronto.</p>
            </div>

        </div>

        <!-- Pestaña Constancia Finalización -->
<div class="tab-pane fade" id="finalizacion" role="tabpanel" aria-labelledby="finalizacion-tab">
    <br>
    <h3>Constancia de Finalización</h3>
    <br>
    <h2 style="font-size: 1.2em; color: #666; margin-bottom: 20px;">Adjuntar Carta de Finalización</h2>
    <!-- Formulario para cargar el PDF -->
    <form id="finalizacionForm" action="{{ route('subirFinalizacion') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="pdf_file" class="font-weight-bold">Selecciona un archivo PDF:</label>
            <input type="file" name="archivo" id="pdf_file" class="form-control" accept=".pdf" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
        </div>
    </form>

    <!-- CONFIRMACION -->
    <div id="confirmacionPractica" class="container mt-5 p-4 border rounded shadow-sm bg-white d-none">
        <!-- Encabezado -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo UNAH" class="img-fluid" style="width: 80px;">
            <h2 class="mt-3 text-uppercase text-dark">Universidad Nacional Autónoma de Honduras</h2>
            <h3 class="text-secondary">Dirección de Práctica Profesional Supervisada</h3>
            <hr class="w-50 mx-auto">
        </div>
        
        <!-- Contenido Principal -->
        <div class="text-center">
            <i class="fas fa-check-circle text-success" style="font-size: 70px;"></i>
            <h4 class="mt-4 text-primary">¡Constancia de Finalización Recibida!</h4>
            <p class="mt-3 text-muted">Nos complace informarte que hemos recibido tu constancia de finalización de práctica profesional.</p>
            <p>Nos encontramos en proceso de revisión y validación de tu documentación. Una vez completado este paso, te notificaremos por correo electrónico el estado de tu práctica profesional.</p>
        </div>
        
        <!-- Mensaje de Agradecimiento -->
        <div class="text-center mt-4">
            <h5 class="text-secondary">¡Felicitaciones por este logro!</h5>
            <p class="text-muted">Agradecemos tu dedicación y esfuerzo durante esta etapa crucial de tu formación académica. Te deseamos mucho éxito en tus futuros proyectos profesionales.</p>
        </div>
        
        <!-- Pie de página -->
        <div class="text-center mt-4">
            <p class="text-muted">Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en comunicarte con nosotros al correo:</p>
            <a href="mailto:adminpps.dia@unah.edu.hn" class="text-decoration-none">adminpps.dia@unah.edu.hn</a>
        </div>
    </div>


        </div>
</div>


<!-- Modal Iniciar Solicitud -->
<div class="modal fade" id="iniciarSolicitudModal" tabindex="-1" aria-labelledby="iniciarSolicitudModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iniciarSolicitudModalLabel">Iniciar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="iniciarSolicitudForm">
                    @csrf
                    @auth
                        <div class="mb-3">
                            <label for="nombreCompleto" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" value="{{ strtoupper(Auth::user()->name) }}" required readonly>
                            <div class="invalid-feedback">
                                Solo letras mayúsculas y un espacio entre nombres.
                            </div>
                        </div>

                        <!-- Campo No. Cuenta: Solo números -->
                        <div class="mb-3">
                            <label for="noCuenta" class="form-label">No. Cuenta</label>
                            <input type="text" class="form-control" id="noCuenta" name="noCuenta" required>
                            <div class="invalid-feedback">
                                Solo se permiten números.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correoInstitucional" class="form-label">Correo Institucional</label>
                            <input type="email" class="form-control" id="correoInstitucional" name="correoInstitucional" value="{{ Auth::user()->email }}" required readonly>
                            <div class="invalid-feedback">
                                Ingrese un correo electrónico válido.
                            </div>
                        </div>
                    @endauth
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="iniciarSolicitudForm">Guardar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
// Manejar el envío del formulario
document.getElementById('iniciarSolicitudForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío por defecto del formulario

    let formData = new FormData(this); // Crear una instancia de FormData con los datos del formulario

    fetch('{{ route("solicitudes.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            alert('Solicitud creada con éxito');
            
            // Guardar el indicador de la pestaña activa en localStorage
            localStorage.setItem('activeTab', 'colegiacion-tab');

            // Cerrar el modal
            let modal = bootstrap.Modal.getInstance(document.getElementById('iniciarSolicitudModal'));
            if (modal) modal.hide();
        } else {
            // Manejo de errores
            if (data.errors) {
                let errorMessages = Object.values(data.errors).flat().join('\n');
                alert('Errores:\n' + errorMessages);
            } else {
                alert('Ocurrió un error inesperado.');
            }
        }
        // Recargar la página para reflejar los cambios
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al intentar crear la solicitud.');
        location.reload();
    });
});

// Activar, resaltar y mostrar la pestaña "Colegiación" al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const estadoSolicitud = @json($estado_solicitud->toArray());
    const activeTab = localStorage.getItem('activeTab'); // Recuperar la pestaña activa de localStorage

    if (estadoSolicitud.includes('SOLICITADA') && activeTab === 'colegiacion-tab') {
        const tabLink = document.getElementById(activeTab); // Obtener la pestaña "Colegiación"
        const tabContent = document.getElementById(tabLink.getAttribute('aria-controls')); // Obtener el contenido asociado

        if (tabLink && tabContent) {
            // Activar la pestaña simulando un clic
            tabLink.click(); // Este método asegura que la pestaña y su contenido se activen correctamente

            // Parpadeo en verde
            let toggle = true;
            const interval = setInterval(() => {
                if (toggle) {
                    tabLink.classList.add('bg-success', 'text-white');
                } else {
                    tabLink.classList.remove('bg-success', 'text-white');
                }
                toggle = !toggle;
            }, 500); // Cambiar colores cada 500ms

            // Detener el parpadeo después de 5 segundos
            setTimeout(() => {
                clearInterval(interval);
                tabLink.classList.remove('bg-success', 'text-white'); // Dejarla en su estado original
            }, 5000);

            // Eliminar el indicador de localStorage para evitar comportamiento no deseado en el futuro
            localStorage.removeItem('activeTab');
        }
    }
});

</script>

<script>
document.getElementById('archivo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file && file.type !== 'application/pdf') {
        alert('Solo puedes subir archivos en formato PDF.');
        event.target.value = '';  // Limpiar el input
    }
});
</script>

<script>
    // Mostrar/ocultar campos adicionales basados en el valor de "labora"
    document.querySelectorAll('input[name="labora"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('trabajoFields').style.display = (this.value === 'Si') ? 'block' : 'none';
        });
    });
</script>

<script>
  $(document).ready(function () {
    // Evento de envío del formulario
    $('#colegiacionForm').on('submit', function (e) {
        e.preventDefault(); // Evitar el envío por defecto del formulario

        let formData = new FormData(this); // Crear una instancia de FormData con los datos del formulario

        // Realizar la solicitud AJAX
        $.ajax({
            url: $(this).attr('action'), // Ruta definida en el formulario
            method: $(this).attr('method'), // Método definido en el formulario (POST)
            data: formData,
            contentType: false, // No establecer el contentType porque estamos enviando FormData
            processData: false, // No procesar los datos (dejamos que FormData se encargue)
            success: function (response) {
                if (response.success) {
                    // Mostrar alerta de éxito
                    alert(response.success);

                    // Verificar si la condición es la correcta para mostrar la pestaña de "Constancia de Presentación"
                    if (response.estado_nota === 'INGRESADA' && response.tipo_practica === 'Normal') {
                        // Activar la pestaña de "Constancia de Presentación"
                        $('#carta-tab').addClass('active');
                        $('#carta').addClass('show active');
                        
                        // Aplicar el efecto de parpadeo a la pestaña
                        $('#carta-tab').addClass('pestaña-parpadeo');
                    }

                    // Recargar la página después de guardar los datos
                    location.reload(); 
                } else {
                    alert('Hubo un problema al guardar la solicitud');
                }
            },
            error: function (xhr) {
                // Si hay un error en la solicitud, mostrar el mensaje de error
                alert('Hubo un error al procesar la solicitud');
            }
        });
    });
});

</script>

<script>
    // Simulando la variable en PHP (en un entorno real esto se pasa desde el servidor)
    let estadoNotaColegiacion = @json($estado_nota_colegiacion);
    let estadoNotaPresentacion = @json($estado_nota_presentacion);
    let estadoNotaIA01 = @json($estado_nota_ppsia01);
    let estadoNotaAceptacion = @json($estado_nota_aceptacion);
    let estadoNotaFinalizacion = @json($estado_nota_finalizacion);

    // Verificar si la variable contiene 'INGRESADA'
    if (estadoNotaColegiacion.includes('INGRESADA')) {
        // Mostrar mensaje de confirmación y ocultar el formulario de colegiación
        const confirmacionColegiacion = document.getElementById('confirmacionColegiacion');
        const colegiacionForm = document.getElementById('colegiacionForm');

        if (confirmacionColegiacion) {
            confirmacionColegiacion.classList.remove('d-none');
            confirmacionColegiacion.classList.add('d-block');
        }

        if (colegiacionForm) {
            colegiacionForm.classList.add('d-none');
        }
    }

    if (estadoNotaPresentacion.includes('INGRESADA')) {
        // Mostrar mensaje de confirmación y ocultar el formulario de presentación
        const confirmacionConstancia = document.getElementById('confirmacionConstancia');
        const presentacionForm = document.getElementById('presentacionForm');

        if (confirmacionConstancia) {
            confirmacionConstancia.classList.remove('d-none');
            confirmacionConstancia.classList.add('d-block');
        }

        if (presentacionForm) {
            presentacionForm.classList.add('d-none');
        }
    }
    
    if (estadoNotaIA01.includes('INGRESADA')) {
    // Mostrar mensaje de confirmación y ocultar el formulario de IA-01
    const confirmacionIA01 = document.getElementById('confirmacionIA01');
    const IA01Form = document.getElementById('IAForm');

    if (confirmacionIA01) {
        confirmacionIA01.classList.remove('d-none');
        confirmacionIA01.classList.add('d-block');
    }

    if (IA01Form) {
        IA01Form.classList.add('d-none');
    }
        
    }

    if (estadoNotaAceptacion.includes('INGRESADA')) {
        // Mostrar mensaje de confirmación y ocultar el formulario de aceptacion
        const confirmacionAceptacion = document.getElementById('confirmacionAceptacion');
        const aceptacionForm = document.getElementById('aceptacionForm');

        if (confirmacionAceptacion) {
            confirmacionAceptacion.classList.remove('d-none');
            confirmacionAceptacion.classList.add('d-block');
        }

        if (aceptacionForm) {
            aceptacionForm.classList.add('d-none');
        }
    }

     // Verificar si la variable contiene 'INGRESADA'
     if (estadoNotaFinalizacion.includes('INGRESADA')) {
        // Mostrar mensaje de confirmación y ocultar el formulario de finalizacion
        const confirmacionPractica = document.getElementById('confirmacionPractica');
        const finalizacionForm = document.getElementById('finalizacionForm');

        if (confirmacionPractica) {
            confirmacionPractica.classList.remove('d-none');
            confirmacionPractica.classList.add('d-block');
        }

        if (finalizacionForm) {
            finalizacionForm.classList.add('d-none');
        }
    }
</script>

<script>
 // Función para calcular Semana Santa
function calcularSemanaSanta(anio) {
    const a = anio % 19;
    const b = Math.floor(anio / 100);
    const c = anio % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const mes = Math.floor((h + l - 7 * m + 114) / 31);
    const dia = ((h + l - 7 * m + 114) % 31) + 1;

    // Fecha de Pascua
    const pascua = new Date(anio, mes - 1, dia);

    // Calcular toda la Semana Santa (lunes a sábado)
    const lunesSanto = new Date(pascua.getFullYear(), pascua.getMonth(), pascua.getDate() - 6);
    const sabadoGloria = new Date(pascua.getFullYear(), pascua.getMonth(), pascua.getDate() - 1);
    const semanaSanta = [];

    let diaActual = new Date(lunesSanto);
    while (diaActual <= sabadoGloria) {
        semanaSanta.push(new Date(diaActual));
        diaActual.setDate(diaActual.getDate() + 1);
    }

    return semanaSanta;
}

// Función para calcular toda la semana de los feriados de octubre
function calcularSemanaOctubre(anio) {
    const primerDiaOctubre = new Date(anio, 9, 1); // Octubre
    let tercerMiercoles = new Date(anio, 9, 1);

    // Encontrar el tercer miércoles
    let contadorMiercoles = 0;
    while (contadorMiercoles < 3) {
        if (tercerMiercoles.getDay() === 3) contadorMiercoles++;
        if (contadorMiercoles < 3) tercerMiercoles.setDate(tercerMiercoles.getDate() + 1);
    }

    // Calcular desde el lunes de esa semana hasta el viernes
    const lunesSemana = new Date(tercerMiercoles);
    lunesSemana.setDate(lunesSemana.getDate() - (tercerMiercoles.getDay() - 1)); // Retroceder al lunes

    const semanaOctubre = [];
    for (let i = 0; i < 5; i++) { // Lunes a viernes
        semanaOctubre.push(new Date(lunesSemana));
        lunesSemana.setDate(lunesSemana.getDate() + 1);
    }

    return semanaOctubre;
}

// Generar la lista dinámica de feriados
function obtenerFeriados(anio) {
    const semanaSanta = calcularSemanaSanta(anio);
    const semanaOctubre = calcularSemanaOctubre(anio);

    return [
        `${anio}-01-01`, // Año Nuevo
        ...semanaSanta.map(fecha => fecha.toISOString().split("T")[0]),
        `${anio}-05-01`, // Día del Trabajador
        `${anio}-09-15`, // Día de la Independencia
        ...semanaOctubre.map(fecha => fecha.toISOString().split("T")[0]),
        `${anio}-12-25`, // Navidad
    ];
}

// Generar feriados para el año actual
const anioActual = new Date().getFullYear();
const feriadosHonduras = obtenerFeriados(anioActual);

// Función para verificar si una fecha es feriado
function esFeriado(fecha) {
    const fechaStr = fecha.toISOString().split("T")[0];
    return feriadosHonduras.includes(fechaStr);
}

// Función principal
function calcularFechaFin() {
    const fechaInicio = document.getElementById("fechaInicio").value;
    const horaInicio1 = document.getElementById("horaInicioLunesViernes1").value;
    const horaFin1 = document.getElementById("horaFinLunesViernes1").value;
    const horaInicio2 = document.getElementById("horaInicioLunesViernes2").value;
    const horaFin2 = document.getElementById("horaFinLunesViernes2").value;
    const horaInicioSabado = document.getElementById("horaInicioSabado").value;
    const horaFinSabado = document.getElementById("horaFinSabado").value;

    if (!fechaInicio) {
        document.getElementById("fechaFinalizacion").value = "";
        return;
    }

    const horasLunesViernes = calcularHorasDiarias(horaInicio1, horaFin1) + calcularHorasDiarias(horaInicio2, horaFin2);
    const horasSabado = calcularHorasDiarias(horaInicioSabado, horaFinSabado);

    if (horasLunesViernes === 0 && horasSabado === 0) {
        document.getElementById("fechaFinalizacion").value = "";
        return;
    }

    let horasTotales = 0;
    let fechaActual = new Date(fechaInicio);
    const diasLaborales = [1, 2, 3, 4, 5];
    const diaSabado = 6;

    while (horasTotales <= 800) {
        const diaSemana = fechaActual.getDay();
        if (diasLaborales.includes(diaSemana) && !esFeriado(fechaActual)) {
            horasTotales += horasLunesViernes;
        } else if (diaSemana === diaSabado && !esFeriado(fechaActual)) {
            horasTotales += horasSabado;
        }
        fechaActual.setDate(fechaActual.getDate() + 1);
    }

    const fechaFin = fechaActual.toISOString().split("T")[0];
    document.getElementById("fechaFinalizacion").value = fechaFin;
}

// Calcular horas diarias
function calcularHorasDiarias(horaInicio, horaFin) {
    if (!horaInicio || !horaFin) return 0;
    const [horaInicioH, horaInicioM] = horaInicio.split(":").map(Number);
    const [horaFinH, horaFinM] = horaFin.split(":").map(Number);
    const inicio = horaInicioH + horaInicioM / 60;
    const fin = horaFinH + horaFinM / 60;
    return Math.max(fin - inicio, 0);
}

// Asociar eventos
document.querySelectorAll("#fechaInicio, #horaInicioLunesViernes1, #horaFinLunesViernes1, #horaInicioLunesViernes2, #horaFinLunesViernes2, #horaInicioSabado, #horaFinSabado")
    .forEach(element => {
        element.addEventListener("change", calcularFechaFin);
    });
</script>
@stop

