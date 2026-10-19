@extends('layouts.app')

@section('title', 'Huéspedes - HotelH9')
@section('page-title', 'Huéspedes')
@section('menu-huespedes', 'active')

@push('styles')
<style>
    .huespedes-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .busqueda-form {
        display: flex;
        gap: 10px;
    }

    .input-busqueda {
        position: relative;
    }

    .input-busqueda i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }

    .input-busqueda input {
        width: 320px;
        height: 40px;
        padding: 0 12px 0 38px;
        border: 1px solid #d7dfeb;
        border-radius: 8px;
        outline: none;
    }

    .input-busqueda input:focus {
        border-color: #2563eb;
    }

    .btn-buscar {
        height: 40px;
        padding: 0 18px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-limpiar {
        height: 40px;
        display: inline-flex;
        align-items: center;
        padding: 0 15px;
        border: 1px solid #d7dfeb;
        border-radius: 7px;
        background: white;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-nuevo {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        background: #2563eb;
        color: white;
        border-radius: 7px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .tabla-container {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
    }

    .tabla-responsive {
        overflow-x: auto;
    }

    .tabla-huespedes {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-huespedes thead {
        background: #f6f8fb;
    }

    .tabla-huespedes th {
        padding: 13px;
        text-align: left;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #dbe3ee;
    }

    .tabla-huespedes td {
        padding: 14px 13px;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabla-huespedes tbody tr:hover {
        background: #f8fafc;
    }

    .nombre-huesped {
        font-weight: 600;
    }

    .dato-secundario {
        color: #64748b;
    }

    .acciones {
        display: flex;
        gap: 8px;
    }

    .btn-accion {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dbe3ee;
        border-radius: 6px;
        background: white;
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-accion:hover, .btn-accion:focus-visible {
        color: #1d4ed8;
        background: #eff6ff;
        border-color: #93c5fd;
        outline-offset: 2px;
    }

    .tabla-footer {
        margin-top: 22px;
        color: #64748b;
        font-size: 14px;
    }

    .alert-success {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 7px;
        background: #e7f8f1;
        color: #047857;
        font-size: 14px;
    }

    .sin-resultados {
        padding: 35px !important;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 850px) {
        .huespedes-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .busqueda-form {
            flex-wrap: wrap;
        }

        .input-busqueda input {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="huespedes-toolbar">

    <form method="GET"
          action="{{ route('huespedes.index') }}"
          class="busqueda-form">

        <div class="input-busqueda">
            <i class="bi bi-search"></i>

            <input
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Buscar por nombre, RUT o email..."
            >
        </div>

        <button type="submit" class="btn-buscar">
            Buscar
        </button>

        @if (request('buscar'))
            <a href="{{ route('huespedes.index') }}"
               class="btn-limpiar">
                Limpiar
            </a>
        @endif

    </form>

    <a href="{{ route('huespedes.create') }}"
       class="btn-nuevo">

        <i class="bi bi-plus-lg"></i>
        Nuevo huésped

    </a>

</div>

<div class="tabla-container">

    <div class="tabla-responsive">

        <table class="tabla-huespedes">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($huespedes as $huesped)

                    <tr>

                        <td class="nombre-huesped">
                            {{ $huesped->nombre }}
                            {{ $huesped->apellido }}
                        </td>

                        <td>
                            {{ $huesped->rut }}
                        </td>

                        <td class="dato-secundario">
                            {{ $huesped->telefono ?? '-' }}
                        </td>

                        <td class="dato-secundario">
                            {{ $huesped->email ?? '-' }}
                        </td>

                        <td class="dato-secundario">
                            {{ $huesped->direccion ?? '-' }}
                        </td>

                        <td>
                            <div class="acciones">

                                <a href="{{ route('huespedes.show', $huesped->_id) }}"
                                   class="btn-accion" title="Ver huésped"
                                   aria-label="Ver datos de {{ $huesped->nombre }} {{ $huesped->apellido }}">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </a>

                                <a href="{{ route('huespedes.edit', $huesped->_id) }}"
                                   class="btn-accion" title="Editar huésped"
                                   aria-label="Editar datos de {{ $huesped->nombre }} {{ $huesped->apellido }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6"
                            class="sin-resultados">

                            No se encontraron huéspedes.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="tabla-footer">

        {{ $huespedes->count() }}

        @if ($huespedes->count() == 1)
            huésped registrado
        @else
            huéspedes registrados
        @endif

    </div>

</div>

@endsection