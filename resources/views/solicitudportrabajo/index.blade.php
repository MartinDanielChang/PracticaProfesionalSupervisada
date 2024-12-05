@extends('adminlte::page')

@section('title', 'PPS-Modulo De Solicitudes a PPS')

@section('content_header')
    <h1>Gestión de Solicitudes a PPS</h1>
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
            @if($estadocolegiacion->contains('INGRESADA'))
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="carta-tab" data-bs-toggle="tab" href="#carta" role="tab" aria-controls="carta" aria-selected="false">Carta Presentación</a>
            </li>
            @endif
            @if($estadopresentacion->contains('INGRESADA'))
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="formato-tab" data-bs-toggle="tab" href="#formato" role="tab" aria-controls="formato" aria-selected="false">Formato IA-02</a>
            </li>
            @endif
            @if($estadoppsia02->contains('INGRESADA'))
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="constancia-tab" data-bs-toggle="tab" href="#constancia" role="tab" aria-controls="constancia" aria-selected="false">Constancia Aceptación</a>
            </li>
            @endif
            @if($estadoaceptacion->contains('INGRESADA'))
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="finalizacion-tab" data-bs-toggle="tab" href="#finalizacion" role="tab" aria-controls="finalizacion" aria-selected="false">Constancia de Finalización</a>
            </li>
            @endif
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- Pestaña Solicitudes -->
            <div class="tab-pane fade show active" id="solicitudes" role="tabpanel" aria-labelledby="solicitudes-tab">
                <br>
                <h3>Solicitudes</h3>
                <br>
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
            <!-- Pestaña Colegiación -->
            <div class="tab-pane fade" id="colegiacion" role="tabpanel" aria-labelledby="colegiacion-tab">
            <div class="container mt-4">
        <h3 class="text-dark mb-4">Colegiación</h3>
        <h5 class="text-secondary mb-4">Adjuntar Colegiación y Datos</h5>
        <!-- Formulario de Colegiación -->
        <form id="colegiacionForm" action="{{ route('subirArchivo') }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Campo para adjuntar archivo PDF -->
            <div class="mb-4">
                <label for="archivo" class="form-label fw-bold">Adjuntar archivo PDF:</label>
                <input type="file" id="archivo" name="archivo" accept=".pdf" required class="form-control">
            </div>

            <!-- Empresa donde se realizará la práctica profesional -->
            <div class="mb-3">
                <label for="empresaPractica" class="form-label">Empresa para la práctica profesional</label>
                <input type="text" class="form-control" id="empresaPractica" name="empresaPractica">
            </div>

            <!-- Sección "Actualmente labora" con campos condicionales -->
            <div class="mb-3">
                <label class="form-label">Actualmente labora:</label>
                <div>
                    <label><input type="radio" id="laboraSi" name="labora" value="Si"> Sí</label>
                    <label class="ms-3"><input type="radio" id="laboraNo" name="labora" value="No" checked> No</label>
                </div>
            </div>

            <!-- Campos adicionales solo si se selecciona "Sí" en "Labora" -->
            <div id="trabajoFields" style="display: none;">
                <div class="mb-3">
                    <label for="nombreEmpresa" class="form-label">Nombre de la Empresa</label>
                    <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa">
                </div>
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

            <!-- Botón de envío -->
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

            <!-- Pestaña Formato IA-02 -->
            <div class="tab-pane fade" id="formato" role="tabpanel" aria-labelledby="formato-tab">
                <h3 class="text-center my-4">Formato IA-02</h3>
                <form id="formIA02" action="{{ route('subirIA02') }}" method="POST" class="container p-4 shadow rounded bg-light" enctype="multipart/form-data">
                @csrf                   
                <!-- Selección de Horario -->
                    <h4 class="text-primary mb-3">I. Selección de Horario y Fechas</h4>

                    <!-- Primer horario de lunes a viernes -->
                    <div class="row mb-3" style="overflow-x: auto; max-width: 100%;">
                        <div class="col" style="min-width: 300px;">
                            <label for="horaInicioLunesViernes1" class="form-label">Hora de Inicio (Lunes a Viernes - 1)</label>
                            <input type="time" class="form-control" id="horaInicioLunesViernes1" name="horaInicioLunesViernes1" required>
                        </div>
                        <div class="col" style="min-width: 300px;">
                            <label for="horaFinLunesViernes1" class="form-label">Hora de Finalización (Lunes a Viernes - 1)</label>
                            <input type="time" class="form-control" id="horaFinLunesViernes1" name="horaFinLunesViernes1" required>
                        </div>
                    </div>

                    <!-- Segundo horario de lunes a viernes -->
                    <div class="row mb-3" style="overflow-x: auto; max-width: 100%;">
                        <div class="col" style="min-width: 300px;">
                            <label for="horaInicioLunesViernes2" class="form-label">Hora de Inicio (Lunes a Viernes - 2)</label>
                            <input type="time" class="form-control" id="horaInicioLunesViernes2" name="horaInicioLunesViernes2">
                        </div>
                        <div class="col" style="min-width: 300px;">
                            <label for="horaFinLunesViernes2" class="form-label">Hora de Finalización (Lunes a Viernes - 2)</label>
                            <input type="time" class="form-control" id="horaFinLunesViernes2" name="horaFinLunesViernes2">
                        </div>
                    </div>

                    <!-- Horarios de sábado -->
                    <div class="row mb-3">
                        <div class="col" style="min-width: 300px;">
                            <label for="horaInicioSabado" class="form-label">Hora de Inicio (Sábado)</label>
                            <input type="time" class="form-control" id="horaInicioSabado" name="horaInicioSabado">
                        </div>
                        <div class="col" style="min-width: 300px;">
                            <label for="horaFinSabado" class="form-label">Hora de Finalización (Sábado)</label>
                            <input type="time" class="form-control" id="horaFinSabado" name="horaFinSabado">
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
                            <input type="date" class="form-control" id="fechaFinalizacion" name="fechaFinalizacion" readonly>
                            </div>
                    </div>

        <!-- Información del Puesto de Trabajo -->
        <h4 class="text-primary mt-4 mb-3">II. Información del Puesto de Trabajo</h4>
        <div class="mb-3">
            <label for="puesto" class="form-label">* Nombre del puesto o cargo:</label>
            <input type="text" id="puesto" name="puesto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="departamento" class="form-label">Departamento al que pertenece:</label>
            <input type="text" id="departamento" name="departamento" class="form-control">
        </div>
        <div class="mb-3">
            <label for="fechaIngresoInstitucion" class="form-label">* Fecha de ingreso a la institución:</label>
            <input type="date" id="fechaIngresoInstitucion" name="fechaIngresoInstitucion" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="fechaInicioPuesto" class="form-label">* Fecha de inicio en el puesto actual:</label>
            <input type="date" id="fechaInicioPuesto" name="fechaInicioPuesto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="jornadaLaboral" class="form-label">* Jornada laboral:</label>
            <select id="jornadaLaboral" name="jornadaLaboral" class="form-select" required>
                <option value="Lunes a Viernes">Lunes a Viernes</option>
                <option value="Lunes a Sábado">Lunes a Sábado</option>
            </select>
        </div>

        <!-- Información de la Institución -->
        <h4 class="text-primary mt-4 mb-3">III. Información de la Institución</h4>
        <div class="mb-3">
            <label for="nombreInstitucion" class="form-label">* Nombre:</label>
            <input type="text" id="nombreInstitucion" name="nombreInstitucion" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="direccionInstitucion" class="form-label">* Dirección:</label>
            <input type="text" id="direccionInstitucion" name="direccionInstitucion" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tipoInstitucion" class="form-label">* Tipo de institución:</label>
            <select id="tipoInstitucion" name="tipoInstitucion" class="form-select" required>
                <option value="Publica">Pública</option>
                <option value="Privada">Privada</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fechaConstitucion" class="form-label">Fecha de constitución:</label>
            <input type="date" id="fechaConstitucion" name="fechaConstitucion" class="form-control">
        </div>

        <!-- Información sobre el Jefe Inmediato -->
        <h4 class="text-primary mt-4 mb-3">IV. Información sobre el Jefe Inmediato</h4>
        <div class="mb-3">
            <label for="nombreJefe" class="form-label">* Nombre:</label>
            <input type="text" id="nombreJefe" name="nombreJefe" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cargoJefe" class="form-label">* Cargo:</label>
            <input type="text" id="cargoJefe" name="cargoJefe" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="correoJefe" class="form-label">* Correo electrónico:</label>
            <input type="email" id="correoJefe" name="correoJefe" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="telefonoJefe" class="form-label">Teléfono:</label>
            <input type="text" id="telefonoJefe" name="telefonoJefe" class="form-control">
        </div>
        <div class="mb-3">
            <label for="celularJefe" class="form-label">* Celular:</label>
            <input type="text" id="celularJefe" name="celularJefe" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nivelAcademico" class="form-label">* Nivel académico:</label>
            <select id="nivelAcademico" name="nivelAcademico" class="form-select" required>
                <option value="Licenciatura">Licenciatura</option>
                <option value="Maestria">Maestría</option>
                <option value="Doctorado">Doctorado</option>
            </select>
        </div>

        <!-- Adjuntar PDF -->
        <h4 class="text-primary mt-4 mb-3">V. Adjuntar Documentación</h4>
        <div class="mb-3">
            <label for="documentoPDF" class="form-label">* Adjuntar archivo (PDF):</label>
            <input type="file" id="documentoPDF" name="documentoPDF" class="form-control" accept=".pdf" required>
        </div>

        <!-- Botón de envío -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Enviar</button>
        </div>
    </form>

     <!-- Confirmación IA-02 -->
<div id="confirmacionIA02" class="mt-5 text-center d-none">
    <i class="fas fa-file-alt text-primary" style="font-size: 50px;"></i>
    <img src="{{ asset('images/Logo-unah.png') }}" alt="Logo UNAH" class="img-fluid my-3" style="width: 60px;">
    <h2 class="text-success">¡Formato IA-02 Recibida!</h2>
    <p>Tu documentación ha sido recibida exitosamente. Nuestro equipo la revisará y te notificará pronto.</p>
</div>
</div>


            <!-- Pestaña Constancia Aceptación -->
            <div class="tab-pane fade" id="constancia" role="tabpanel" aria-labelledby="constancia-tab">
                <h3>Constancia Aceptación</h3>
                <p>Contenido de la pestaña de Constancia Aceptación aquí.</p>
                <!-- Agrega aquí tu contenido específico para la Constancia -->
            </div>
            <!-- Pestaña Constancia Finalización -->
            <div class="tab-pane fade" id="finalizacion" role="tabpanel" aria-labelledby="finalizacion-tab">
                <h3>Constancia de Finalización</h3>
                <p>Contenido de la pestaña de Constancia de Finalización aquí.</p>
                <!-- Agrega aquí tu contenido específico para la Constancia de Finalización -->
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
document.getElementById('iniciarSolicitudForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío por defecto del formulario

    let formData = new FormData(this);  // Crear una instancia de FormData con los datos del formulario

    fetch('{{ route("solicitudportrabajo.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())  // Convertir la respuesta a JSON
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            alert('Solicitud creada con éxito');
            
            // Cerrar el modal usando Bootstrap 5
            let modal = bootstrap.Modal.getInstance(document.getElementById('iniciarSolicitudModal'));
            if (modal) {
                modal.hide();
            }
        } else {
            // Mostrar mensajes de error si los hay
            if (data.errors) {
                let errorMessages = Object.values(data.errors).flat().join('\n');
                alert('Errores:\n' + errorMessages);
            } else {
                alert('Ocurrió un error inesperado.');
            }
        }
        // Recargar la página para reflejar los cambios, ya sea que haya éxito o errores
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al intentar crear la solicitud.');
        location.reload();
    });
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
        $('#colegiacionForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        // Mostrar un mensaje de éxito (opcional)
                        console.log('Solicitud guardada exitosamente');
                        
                        // Recargar la página
                        location.reload();
                    }
                },
                error: function (xhr) {
                    alert('Hubo un error al subir tu archivo. Por favor, intenta de nuevo.');
                }
            });
        });
    });
</script>

<script>
    // Simulando la variable en PHP (en un entorno real esto se pasa desde el servidor)
    let estadoNotaColegiacion = @json($estadocolegiacion);
    let estadoNotaPresentacion = @json($estadopresentacion);
    let estadoNotaIA02 = @json($estadoppsia02);
    let estadoNotaAceptacion = @json($estadoaceptacion);
    let estadoNotaFinalizacion = @json($estadofinalizacion);

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
    
    if (estadoNotaIA02.includes('INGRESADA')) {
    // Mostrar mensaje de confirmación y ocultar el formulario de IA-01
    const confirmacionIA02 = document.getElementById('confirmacionIA01');
    const IA02Form = document.getElementById('IAForm');

    if (confirmacionIA02) {
        confirmacionIA02.classList.remove('d-none');
        confirmacionIA02.classList.add('d-block');
    }

    if (IA02Form) {
        IA02Form.classList.add('d-none');
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
document.getElementById('archivo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file && file.type !== 'application/pdf') {
        alert('Solo puedes subir archivos en formato PDF.');
        event.target.value = '';  // Limpiar el input
    }
});
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