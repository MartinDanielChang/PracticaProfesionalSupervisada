@extends('adminlte::page')

@section('title', 'PPS-Modulo De Carga Archivos')

@section('content_header')
    <h1>FORMATOS</h1>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Mostrar mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Botón para abrir el modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subirArchivoModal">Subir Archivo</button>
        <br><br>
        <table id="tableId" class="table table-bordered" style="width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th>Documentos</th>
                    <th>URL</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach (Storage::files('public/formatos') as $archivo)
                    <tr class="{{ $loop->even ? 'table-secondary' : 'table-light' }}">
                        <td>{{ basename($archivo) }}</td>
                        <td><a href="{{ Storage::url($archivo) }}" download>Descargar</a></td>
                        <td>
                            <form action="{{ route('eliminar.archivo', ['archivo' => basename($archivo)]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este archivo?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal de subida de archivos -->
    <div class="modal fade" id="subirArchivoModal" tabindex="-1" aria-labelledby="subirArchivoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSubirArchivo" action="{{ route('subirArchivo') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="subirArchivoModalLabel">Subir Archivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="archivo" class="form-label">Seleccionar archivo (PDF):</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" required accept="application/pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Subir Archivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableId').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
    </script>
@stop
