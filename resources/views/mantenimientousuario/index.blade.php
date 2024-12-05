@extends('adminlte::page')

@section('title', 'PPS-Modulo De Mantenimiento Usuario')

@section('content_header')
@stop

@section('content')
<br>
<h1>Mantenimiento Usuario</h1>
<br>
<a class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
    <span>Crear</span>
</a>
<br>
<br>
<div class="table-responsive">
<table id="tableId" class="display nowrap table table-bordered" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th>#</th> <!-- Columna para el número -->
            <th>Nombre</th>
            <th>Correo Institucional</th>
            <th>DNI</th>
            <th>Carnet</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- Muestra el número de fila -->
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_identidad }}</td>
                <td>{{ $user->no_cuenta }}</td>
                <td>{{ $user->NOM_ROL }}</td>
                <td>
                    <!-- Botón de Editar -->
                    <button class="btn btn-warning btn-sm btn-edit"
                        data-id="{{ $user->id }}"
                        data-nombre="{{ $user->name }}"
                        data-correo="{{ $user->email }}"
                        data-dni="{{ $user->no_identidad }}"
                        data-carnet="{{ $user->no_cuenta }}"
                        data-rol="{{ $user->NOM_ROL }}"
                        data-bs-toggle="modal"
                        data-bs-target="#editarUsuarioModal">
                        <i class="fas fa-edit"></i>
                    </button>

                    <!-- Botón de Eliminar -->
                    <form action="{{ route('mantenimientousuario.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Modal Crear Usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearUsuarioModalLabel">Creación de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mantenimientousuario.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="num" class="form-label">Número de identificación carnet:</label>
                        <input type="text" class="form-control" id="num" name="numero" placeholder="Ingrese el número aquí" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI:</label>
                        <input type="text" class="form-control" id="dni" name="no_identidad" placeholder="DNI" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono2" placeholder="Teléfono" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol:</label>
                        <select class="form-control" id="rol" name="COD_ROL" required>
                            <option value="1">Administrador</option>
                            <option value="2">Estudiante</option>
                            <option value="3">Supervisor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre_editar" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre_editar" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo_editar" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo_editar" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="dni_editar" class="form-label">DNI:</label>
                        <input type="text" class="form-control" id="dni_editar" name="no_identidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="carnet_editar" class="form-label">Carnet:</label>
                        <input type="text" class="form-control" id="carnet_editar" name="no_cuenta" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol_editar" class="form-label">Rol:</label>
                        <select class="form-control" id="rol_editar" name="COD_ROL" required>
                            <option value="1">Administrador</option>
                            <option value="2">Estudiante</option>
                            <option value="3">Supervisor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#tableId').DataTable({
        responsive: true,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const correo = $(this).data('correo');
        const dni = $(this).data('dni');
        const carnet = $(this).data('carnet');
        const rol = $(this).data('rol');

        // Configurar la acción del formulario
        $('#formEditarUsuario').attr('action', `/mantenimientousuario/${id}`);

        // Rellenar los campos
        $('#nombre_editar').val(nombre);
        $('#correo_editar').val(correo);
        $('#dni_editar').val(dni);
        $('#carnet_editar').val(carnet);

        // Seleccionar el rol actual en el select
        $('#rol_editar').val(rol);
    });
});
</script>
@stop
