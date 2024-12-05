@extends('adminlte::page')

@section('title', 'PPS-Modulo De ')

@section('content_header')

@stop

@section('content')
<div class="container">
    <br>
    <h1>Gestión de Permisos</h1>
    <br>
    <form method="POST" action="">
        @csrf
        @method('PUT')
        
        <div>
            <label>Rol</label>
            <input type="text" value="" readonly>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Módulo</th>
                    <th>Ver</th>
                    <th>Crear</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="">
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" name="">
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Regresar</a>
    </form>
</div>
@stop

@section('css')
<style>
    /* Estilos para los toggle switches */
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #4CAF50;
    }

    input:checked + .slider:before {
        transform: translateX(14px);
    }
</style>
@stop

@section('js')

@stop