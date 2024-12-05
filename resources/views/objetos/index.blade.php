@extends('adminlte::page')

@section('title', 'Administración de Objetos')

@section('content_header')
    <h1>Administración de Objetos</h1>
@stop

@section('content')
    <!-- Botón para agregar objetos -->
    <a class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#crearObjetoModal">
        <span>Crear Objeto</span>
    </a>

    <!-- Tabla de Objetos -->
    <h3>Lista de Objetos</h3>
    <div class="table-responsive">
        <table id="objetosTable" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Objeto</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objetos as $objeto)
                    <tr>
                        <td>{{ $objeto->COD_OBJETO }}</td>
                        <td>{{ $objeto->NOM_OBJETO }}</td>
                        <td>{{ $objeto->TIP_OBJETO }}</td>
                        <td>{{ $objeto->DES_OBJETO }}</td>
                        <td>{{ $objeto->IND_OBJETO ? 'Activo' : 'Inactivo' }}</td>
                        <td>{{ $objeto->USR_ADD }}</td>
                        <td>
                            <!-- Botón de Editar -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editarObjetoModal{{ $objeto->id }}" class="btn btn-warning btn-sm">Editar</a>

                            <!-- Botón de Eliminar -->
                            <form action="{{ route('objetos.destroy', $objeto) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este objeto?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal para Editar Objeto -->
                    <div class="modal fade" id="editarObjetoModal{{ $objeto->id }}" tabindex="-1" aria-labelledby="editarObjetoModalLabel{{ $objeto->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarObjetoModalLabel{{ $objeto->id }}">Editar Objeto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('objetos.update', $objeto) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="NOM_OBJETO" class="form-label">Nombre del Objeto</label>
                                            <input type="text" class="form-control" id="NOM_OBJETO" name="NOM_OBJETO" value="{{ $objeto->NOM_OBJETO }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="TIP_OBJETO" class="form-label">Tipo</label>
                                            <input type="text" class="form-control" id="TIP_OBJETO" name="TIP_OBJETO" value="{{ $objeto->TIP_OBJETO }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="DES_OBJETO" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="DES_OBJETO" name="DES_OBJETO">{{ $objeto->DES_OBJETO }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="IND_OBJETO" class="form-label">Estado</label>
                                            <select class="form-control" id="IND_OBJETO" name="IND_OBJETO" required>
                                                <option value="1" {{ $objeto->IND_OBJETO ? 'selected' : '' }}>Activo</option>
                                                <option value="0" {{ !$objeto->IND_OBJETO ? 'selected' : '' }}>Inactivo</option>
                                            </select>
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

    <!-- Modal para Crear Objeto -->
    <div class="modal fade" id="crearObjetoModal" tabindex="-1" aria-labelledby="crearObjetoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearObjetoModalLabel">Crear Objeto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('objetos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="NOM_OBJETO" class="form-label">Nombre del Objeto</label>
                            <input type="text" class="form-control" id="NOM_OBJETO" name="NOM_OBJETO" required>
                        </div>
                        <div class="mb-3">
                            <label for="TIP_OBJETO" class="form-label">Tipo</label>
                            <input type="text" class="form-control" id="TIP_OBJETO" name="TIP_OBJETO" required>
                        </div>
                        <div class="mb-3">
                            <label for="DES_OBJETO" class="form-label">Descripción</label>
                            <textarea class="form-control" id="DES_OBJETO" name="DES_OBJETO"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="IND_OBJETO" class="form-label">Estado</label>
                            <select class="form-control" id="IND_OBJETO" name="IND_OBJETO" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
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
            $('#objetosTable').DataTable({
                responsive: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
@stop
