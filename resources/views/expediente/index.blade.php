@extends('adminlte::page')

@section('title', 'PPS - Módulo De Solicitudes')

@section('content_header')
    <h1>Solicitudes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="solicitudesTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="todas-tab" data-bs-toggle="tab" href="#todas" role="tab" aria-controls="todas" aria-selected="true">Todas las Solicitudes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="aprobadas-tab" data-bs-toggle="tab" href="#aprobadas" role="tab" aria-controls="aprobadas" aria-selected="false">Solicitudes Aprobadas</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="solicitudesTabsContent">
                {{-- Pestaña Todas las Solicitudes --}}
                <div class="tab-pane fade show active" id="todas" role="tabpanel" aria-labelledby="todas-tab">
                    <div class="table-responsive">
                        <table id="todasSolicitudesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitudes as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id_solicitud }}</td>
                                        <td>{{ $solicitud->user->name ?? 'Usuario desconocido' }}</td>
                                        <td>{{ $solicitud->descripcion }}</td>
                                        <td>
                                            <span class="badge bg-{{ $solicitud->estado_solicitud == 'APROBADA' ? 'success' : ($solicitud->estado_solicitud == 'CANCELADA' ? 'danger' : 'warning') }}">
                                                {{ $solicitud->estado_solicitud }}
                                            </span>
                                        </td>
                                        <td>{{ $solicitud->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="estadoMenu{{ $solicitud->id_solicitud }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Cambiar Estado
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="estadoMenu{{ $solicitud->id_solicitud }}">
                                                    <li>
                                                        <form action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="dropdown-item" type="submit" name="estado" value="APROBADA">APROBADA</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="dropdown-item" type="submit" name="estado" value="CANCELADA">CANCELADA</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            <a href="{{ route('solicitudes.download', $solicitud->id_solicitud) }}" class="btn btn-primary btn-sm mt-2">
                                                Descargar Expediente
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pestaña Solicitudes Aprobadas --}}
                <div class="tab-pane fade" id="aprobadas" role="tabpanel" aria-labelledby="aprobadas-tab">
                    <div class="table-responsive">
                        <table id="aprobadasSolicitudesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Fecha de Solicitud</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($solicitudesAprobadas as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id_solicitud }}</td>
                                        <td>{{ $solicitud->user->name ?? 'Usuario desconocido' }}</td>
                                        <td>{{ $solicitud->descripcion }}</td>
                                        <td>
                                            <span class="badge bg-success">APROBADA</span>
                                        </td>
                                        <td>{{ $solicitud->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="estadoMenu{{ $solicitud->id_solicitud }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Cambiar Estado
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="estadoMenu{{ $solicitud->id_solicitud }}">
                                                    <li>
                                                        <form action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="dropdown-item" type="submit" name="estado" value="FINALIZADA">FINALIZADA</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="dropdown-item" type="submit" name="estado" value="CANCELADA">CANCELADA</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
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
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@stop

@section('js')
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#todasSolicitudesTable, #aprobadasSolicitudesTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json'
                }
            });

            // Mantener activa la pestaña seleccionada después de recargar
            const activeTab = localStorage.getItem('activeTab') || 'todas';
            const tab = new bootstrap.Tab(document.querySelector(`#${activeTab}-tab`));
            tab.show();

            // Guardar la pestaña activa en localStorage
            document.querySelectorAll('#solicitudesTabs a[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    localStorage.setItem('activeTab', event.target.getAttribute('id').split('-')[0]);
                });
            });
        });
    </script>
@stop
