@extends('adminlte::page')

@section('title', 'Supervisores y Estudiantes')

@section('content_header')
    <h1>Supervisores y sus Estudiantes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if($supervisores->isEmpty())
                <div class="alert alert-warning">
                    No hay supervisores habilitados con estudiantes asignados.
                </div>
            @else
                <div class="table-responsive">
                    @foreach($supervisores as $supervisor)
                        <div class="mb-4">
                            <h4>Supervisor: {{ $supervisor->nombre }}</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Estudiante</th>
                                        <th>Supervisión 1</th>
                                        <th>Supervisión 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supervisor->estudiantes as $asignacion)
                                        <tr>
                                            <td>{{ $asignacion->estudiante->id }}</td>
                                            <td>{{ $asignacion->estudiante->name }}</td>
                                            <td>
                                                @if($asignacion->supervision1_file)
                                                    <a href="{{ asset('storage/' . $asignacion->supervision1_file) }}" target="_blank">Descargar</a>
                                                    <span class="badge bg-success">Enviado</span>
                                                @else
                                                    @if(auth()->user()->COD_ROL === 3 && auth()->user()->supervisor->id_supervisor === $supervisor->id_supervisor)
                                                        <form action="{{ route('supervisores.subirArchivo', $asignacion->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" name="supervision1_file" required>
                                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Subir Supervisión 1</button>
                                                        </form>
                                                    @else
                                                        <span class="text-danger">Aún no subida</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($asignacion->supervision2_file)
                                                    <a href="{{ asset('storage/' . $asignacion->supervision2_file) }}" target="_blank">Descargar</a>
                                                    <span class="badge bg-success">Enviado</span>
                                                @else
                                                    @if(auth()->user()->COD_ROL === 3 && auth()->user()->supervisor->id_supervisor === $supervisor->id_supervisor)
                                                        <form action="{{ route('supervisores.subirArchivo', $asignacion->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" name="supervision2_file" required>
                                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Subir Supervisión 2</button>
                                                        </form>
                                                    @else
                                                        <span class="text-danger">Aún no subida</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json'
                }
            });
        });
    </script>
@stop
