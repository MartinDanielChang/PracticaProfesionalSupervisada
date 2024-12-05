@extends('adminlte::page')

@section('title', 'Administración de Roles y Permisos')

@section('content_header')
    <h1>Administración de Roles y Permisos</h1>
@stop

@section('content')
    <!-- Botones para agregar roles y permisos -->
    <a class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#crearRolModal">
        <span>Crear Rol</span>
    </a>
    <a class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearPermisoModal">
        <span>Crear Permiso</span>
    </a>

    <!-- Tabla de Roles -->
    <h3>Roles</h3>
    <div class="table-responsive">
        <table id="rolesTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $rol)
                    <tr>
                        <td>{{ $rol->COD_ROL }}</td>
                        <td>{{ $rol->NOM_ROL }}</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editarRolModal{{ $rol->COD_ROL }}" class="btn btn-warning btn-sm">Editar</a>

                            <form action="{{ route('roles.destroy', $rol->COD_ROL) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este rol?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal para Editar Rol -->
                    <div class="modal fade" id="editarRolModal{{ $rol->COD_ROL }}" tabindex="-1" aria-labelledby="editarRolModalLabel{{ $rol->COD_ROL }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarRolModalLabel{{ $rol->COD_ROL }}">Editar Rol</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('roles.update', $rol->COD_ROL) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="NOM_ROL" class="form-label">Nombre del Rol</label>
                                            <input type="text" class="form-control" id="NOM_ROL" name="NOM_ROL" value="{{ $rol->NOM_ROL }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <!-- Tabla de Permisos -->
    <h3>Permisos</h3>
    <div class="table-responsive">
        <table id="permisosTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Rol</th>
                    <th>Objeto</th>
                    <th>Módulo</th>
                    <th>Seleccionar</th>
                    <th>Insertar</th>
                    <th>Actualizar</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permisos as $permiso)
                    <tr>
                        <td>{{ $permiso->COD_PERMISOS }}</td>
                        <td>{{ $permiso->role->NOM_ROL }}</td>
                        <td>{{ $permiso->objeto->NOM_OBJETO }}</td>
                        <td>{{ $permiso->IND_MODULO ? 'Sí' : 'No' }}</td>
                        <td>{{ $permiso->IND_SELECT ? 'Sí' : 'No' }}</td>
                        <td>{{ $permiso->IND_INSERT ? 'Sí' : 'No' }}</td>
                        <td>{{ $permiso->IND_UPDATE ? 'Sí' : 'No' }}</td>
                        <td>{{ $permiso->USR_ADD }}</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editarPermisoModal{{ $permiso->COD_PERMISOS }}" class="btn btn-warning btn-sm">Editar</a>

                            <form action="{{ route('permisos.destroy', $permiso->COD_PERMISOS) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este permiso?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para Crear Rol -->
    <div class="modal fade" id="crearRolModal" tabindex="-1" aria-labelledby="crearRolModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearRolModalLabel">Crear Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="NOM_ROL" class="form-label">Nombre del Rol</label>
                            <input type="text" class="form-control" id="NOM_ROL" name="NOM_ROL" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Permiso -->
    <div class="modal fade" id="crearPermisoModal" tabindex="-1" aria-labelledby="crearPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearPermisoModalLabel">Crear Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('permisos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Selección de Rol -->
                        <div class="mb-3">
                            <label for="COD_ROL" class="form-label">Rol</label>
                            <select class="form-control" id="COD_ROL" name="COD_ROL" required>
                                <option value="" disabled selected>Seleccione un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->COD_ROL }}">{{ $rol->NOM_ROL }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de Objeto -->
                        <div class="form-group">
                        <label for="COD_OBJETO">Objeto</label>
                        <select name="COD_OBJETO" id="COD_OBJETO" class="form-control" required>
                            @foreach($objetos as $objeto)
                                <option value="{{ $objeto->COD_OBJETO }}">{{ $objeto->NOM_OBJETO }}</option>
                            @endforeach
                        </select>
                    </div>

                        <!-- Permisos -->
                         <!-- Checkbox para seleccionar todos -->
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll">
    <label for="selectAll" class="form-check-label">Seleccionar todos</label>
</div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="IND_MODULO" name="IND_MODULO" value="1" checked>
                            <label for="IND_MODULO" class="form-check-label">Acceso al Módulo</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="IND_SELECT" name="IND_SELECT">
                            <label for="IND_SELECT" class="form-check-label">Seleccionar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="IND_INSERT" name="IND_INSERT">
                            <label for="IND_INSERT" class="form-check-label">Insertar</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="IND_UPDATE" name="IND_UPDATE">
                            <label for="IND_UPDATE" class="form-check-label">Actualizar</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal para Editar Permiso -->
@foreach($permisos as $permiso)
<div class="modal fade" id="editarPermisoModal{{ $permiso->COD_PERMISOS }}" tabindex="-1" aria-labelledby="editarPermisoModalLabel{{ $permiso->COD_PERMISOS }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPermisoModalLabel{{ $permiso->COD_PERMISOS }}">Editar Permiso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('permisos.update', $permiso->COD_PERMISOS) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Selección de Rol -->
                    <div class="mb-3">
                        <label for="COD_ROL_{{ $permiso->COD_PERMISOS }}" class="form-label">Rol</label>
                        <select class="form-control" id="COD_ROL_{{ $permiso->COD_PERMISOS }}" name="COD_ROL" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->COD_ROL }}" {{ $permiso->COD_ROL == $rol->COD_ROL ? 'selected' : '' }}>
                                    {{ $rol->NOM_ROL }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selección de Objeto -->
                    <div class="mb-3">
                        <label for="COD_OBJETO_{{ $permiso->COD_PERMISOS }}" class="form-label">Objeto</label>
                        <select class="form-control" id="COD_OBJETO_{{ $permiso->COD_PERMISOS }}" name="COD_OBJETO" required>
                            @foreach($objetos as $objeto)
                                <option value="{{ $objeto->COD_OBJETO }}" {{ $permiso->COD_OBJETO == $objeto->COD_OBJETO ? 'selected' : '' }}>
                                    {{ $objeto->NOM_OBJETO }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Permisos -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="IND_MODULO_{{ $permiso->COD_PERMISOS }}" name="IND_MODULO" value="1" {{ $permiso->IND_MODULO ? 'checked' : '' }}>
                        <label for="IND_MODULO_{{ $permiso->COD_PERMISOS }}" class="form-check-label">Acceso al Módulo</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="IND_SELECT_{{ $permiso->COD_PERMISOS }}" name="IND_SELECT" value="1" {{ $permiso->IND_SELECT ? 'checked' : '' }}>
                        <label for="IND_SELECT_{{ $permiso->COD_PERMISOS }}" class="form-check-label">Seleccionar</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="IND_INSERT_{{ $permiso->COD_PERMISOS }}" name="IND_INSERT" value="1" {{ $permiso->IND_INSERT ? 'checked' : '' }}>
                        <label for="IND_INSERT_{{ $permiso->COD_PERMISOS }}" class="form-check-label">Insertar</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="IND_UPDATE_{{ $permiso->COD_PERMISOS }}" name="IND_UPDATE" value="1" {{ $permiso->IND_UPDATE ? 'checked' : '' }}>
                        <label for="IND_UPDATE_{{ $permiso->COD_PERMISOS }}" class="form-check-label">Actualizar</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#rolesTable, #permisosTable').DataTable({
                responsive: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.form-check-input:not(#selectAll)');

    // Evento para marcar/desmarcar todos
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Actualizar la casilla "Seleccionar todos" si alguna cambia
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else if (Array.from(checkboxes).every(cb => cb.checked)) {
                selectAllCheckbox.checked = true;
            }
        });
    });
});

    </script>
@stop
