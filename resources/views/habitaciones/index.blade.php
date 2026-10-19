@extends('layouts.app')

@section('title', 'Habitaciones - Gestión Hotel')
@section('page-title', 'Habitaciones')
@section('menu-habitaciones', 'active')

@push('styles')
<style>
    .habitaciones-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filtros {
        display: flex;
        gap: 10px;
        align-items: center;
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
        width: 280px;
        height: 40px;
        padding: 0 12px 0 38px;
        border: 1px solid #d7dfeb;
        border-radius: 8px;
        outline: none;
    }

    .filtro-estado {
        height: 40px;
        padding: 0 12px;
        border: 1px solid #d7dfeb;
        border-radius: 8px;
        background: white;
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
    }

    .btn-nueva {
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

    .tabla-habitaciones {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-habitaciones thead {
        background: #f6f8fb;
    }

    .tabla-habitaciones th {
        padding: 13px;
        text-align: left;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        border-bottom: 1px solid #dbe3ee;
    }

    .tabla-habitaciones td {
        padding: 14px 13px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .numero {
        font-weight: 700;
        color: #1e293b;
    }

    .precio {
        font-weight: 600;
    }

    .badge {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-disponible {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-reservada {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-ocupada {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-limpieza {
        background: #fef3c7;
        color: #a16207;
    }

    .badge-fuera {
        background: #e2e8f0;
        color: #475569;
    }

    .estado-form {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .estado-form select {
        height: 35px;
        border: 1px solid #d7dfeb;
        border-radius: 6px;
        padding: 0 8px;
        background: white;
        font-size: 12px;
    }

    .btn-guardar {
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 6px;
        background: #2563eb;
        color: white;
        cursor: pointer;
    }

    .alert-success {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 7px;
        background: #e7f8f1;
        color: #047857;
    }

    .alert-error {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 7px;
        background: #feecec;
        color: #b91c1c;
    }

    .sin-resultados {
        padding: 40px !important;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 900px) {
        .habitaciones-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .filtros {
            flex-wrap: wrap;
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

@if ($errors->any())
    <div class="alert-error">
        <i class="bi bi-exclamation-circle"></i>
        {{ $errors->first() }}
    </div>
@endif

<div class="habitaciones-toolbar">

    <form method="GET"
          action="{{ route('habitaciones.index') }}"
          class="filtros">

        <div class="input-busqueda">
            <i class="bi bi-search"></i>

            <input
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Buscar habitación..."
            >
        </div>

        <select name="estado" class="filtro-estado">
            <option value="">Todos los estados</option>
            <option value="Disponible" {{ request('estado') === 'Disponible' ? 'selected' : '' }}>
                Disponible
            </option>
            <option value="Reservada" {{ request('estado') === 'Reservada' ? 'selected' : '' }}>
                Reservada
            </option>
            <option value="Ocupada" {{ request('estado') === 'Ocupada' ? 'selected' : '' }}>
                Ocupada
            </option>
            <option value="En limpieza" {{ request('estado') === 'En limpieza' ? 'selected' : '' }}>
                En limpieza
            </option>
            <option value="Fuera de servicio" {{ request('estado') === 'Fuera de servicio' ? 'selected' : '' }}>
                Fuera de servicio
            </option>
        </select>

        <button type="submit" class="btn-buscar">
            Buscar
        </button>

        @if (request('buscar') || request('estado'))
            <a href="{{ route('habitaciones.index') }}"
               class="btn-limpiar">
                Limpiar
            </a>
        @endif

    </form>

    <a href="{{ route('habitaciones.create') }}"
       class="btn-nueva">
        <i class="bi bi-plus-lg"></i>
        Nueva habitación
    </a>

</div>

<div class="tabla-container">

    <div class="tabla-responsive">

        <table class="tabla-habitaciones">

            <thead>
                <tr>
                    <th>Habitación</th>
                    <th>Tipo</th>
                    <th>Capacidad</th>
                    <th>Precio / noche</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($habitaciones as $habitacion)

                    @php
                        $claseEstado = match($habitacion->estado) {
                            'Disponible' => 'badge-disponible',
                            'Reservada' => 'badge-reservada',
                            'Ocupada' => 'badge-ocupada',
                            'En limpieza' => 'badge-limpieza',
                            default => 'badge-fuera'
                        };
                    @endphp

                    <tr>

                        <td class="numero">
                            Hab. {{ $habitacion->numero }}
                        </td>

                        <td>
                            {{ $habitacion->tipo }}
                        </td>

                        <td>
                            {{ $habitacion->capacidad }}
                            {{ $habitacion->capacidad == 1 ? 'persona' : 'personas' }}
                        </td>

                        <td class="precio">
                            ${{ number_format($habitacion->precio, 0, ',', '.') }}
                        </td>

                        <td>
                            <span class="badge {{ $claseEstado }}">
                                {{ $habitacion->estado }}
                            </span>
                        </td>

                        <td>

                            <form
                                method="POST"
                                action="{{ route('habitaciones.estado', $habitacion->id_habitacion) }}"
                                class="estado-form"
                            >

                                @csrf
                                @method('PATCH')

                                <select name="estado">

                                    <option value="Disponible"
                                        {{ $habitacion->estado === 'Disponible' ? 'selected' : '' }}>
                                        Disponible
                                    </option>

                                    <option value="Reservada"
                                        {{ $habitacion->estado === 'Reservada' ? 'selected' : '' }}>
                                        Reservada
                                    </option>

                                    <option value="Ocupada"
                                        {{ $habitacion->estado === 'Ocupada' ? 'selected' : '' }}>
                                        Ocupada
                                    </option>

                                    <option value="En limpieza"
                                        {{ $habitacion->estado === 'En limpieza' ? 'selected' : '' }}>
                                        En limpieza
                                    </option>

                                    <option value="Fuera de servicio"
                                        {{ $habitacion->estado === 'Fuera de servicio' ? 'selected' : '' }}>
                                        Fuera de servicio
                                    </option>

                                </select>

                                <button type="submit"
                                        class="btn-guardar">
                                    <i class="bi bi-check-lg"></i>
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="sin-resultados">
                            No se encontraron habitaciones.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection