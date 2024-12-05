@extends('adminlte::page')

@section('title', 'Supervisores')

@section('content_header')
    <h1>Supervisores</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="supervisorTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="habilitados-tab" data-toggle="tab" href="#habilitados" role="tab" aria-controls="habilitados" aria-selected="true">Habilitados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="inhabilitados-tab" data-toggle="tab" href="#inhabilitados" role="tab" aria-controls="inhabilitados" aria-selected="false">Inhabilitados</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="supervisorTabsContent">
                <!-- Supervisores Habilitados -->
                <div class="tab-pane fade show active" id="habilitados" role="tabpanel" aria-labelledby="habilitados-tab">
                    <div class="table-responsive">
                        <table id="habilitadosTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Número de Estudiantes</th>
                                    <th>Estado</th>
                                    <th>Estudiantes Asignados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($habilitados as $supervisor)
                                    <tr>
                                        <td>{{ $supervisor->id_supervisor }}</td>
                                        <td>{{ $supervisor->nombre }}</td>
                                        <td>{{ $supervisor->contacto }}</td>
                                        <td>{{ $supervisor->no_estudiantes }}</td>
                                        <td>
                                            <span class="badge bg-success">Habilitado</span>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach($supervisor->estudiantes as $asignacion)
                                                    <li>{{ $asignacion->estudiante->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <form action="{{ route('supervisores.inhabilitar', $supervisor->id_supervisor) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-ban"></i> Inhabilitar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Supervisores Inhabilitados -->
                <div class="tab-pane fade" id="inhabilitados" role="tabpanel" aria-labelledby="inhabilitados-tab">
                    <div class="table-responsive">
                        <table id="inhabilitadosTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inhabilitados as $supervisor)
                                    <tr>
                                        <td>{{ $supervisor->id_supervisor }}</td>
                                        <td>{{ $supervisor->nombre }}</td>
                                        <td>{{ $supervisor->contacto }}</td>
                                        <td>
                                            <form action="{{ route('supervisores.habilitar', $supervisor->id_supervisor) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Habilitar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
            $('#habilitadosTable, #inhabilitadosTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json'
                }
            });
        });
    </script>
@stop
