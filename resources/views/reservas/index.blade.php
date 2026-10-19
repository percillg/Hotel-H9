@extends('layouts.app')

@section('title', 'Reservas - Gestión Hotel')
@section('page-title', 'Reservas')
@section('menu-reservas', 'active')

@push('styles')
<style>
    /* Barra superior de filtros */
    .reservas-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }

    .filtros {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .input-group {
        position: relative;
    }

    .input-group i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }

    .buscar {
        width: 240px;
        height: 40px;

        padding: 0 12px 0 38px;

        border: 1px solid #d7dfeb;
        border-radius: 8px;

        background: white;
        color: #172033;

        outline: none;
    }

    .buscar:focus,
    .select-estado:focus,
    .fecha:focus {
        border-color: #2563eb;
    }

    .select-estado,
    .fecha {
        height: 40px;

        padding: 0 12px;

        border: 1px solid #d7dfeb;
        border-radius: 8px;

        background: white;
        color: #172033;

        outline: none;
    }

    .fecha {
        width: 150px;
    }

    .btn-buscar {
        height: 40px;

        border: none;
        border-radius: 7px;

        padding: 0 18px;

        background: #2563eb;
        color: white;

        cursor: pointer;
        font-weight: 600;
    }

    .btn-buscar:hover {
        background: #1d4ed8;
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

    /* Nueva reserva */
    .btn-nueva {
        display: flex;
        align-items: center;
        gap: 8px;

        background: #2563eb;
        color: white;

        padding: 11px 18px;

        border-radius: 7px;

        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    /* Tabla */
    .tabla-container {
        background: white;

        border: 1px solid #dbe3ee;
        border-radius: 10px;

        padding: 20px;
    }

    .tabla-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .tabla-reservas {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-reservas thead {
        background: #f6f8fb;
    }

    .tabla-reservas th {
        padding: 12px;

        text-align: left;

        color: #64748b;
        font-size: 12px;
        font-weight: 600;

        border: 1px solid #dbe3ee;
    }

    .tabla-reservas td {
        padding: 12px;

        border-bottom: 1px solid #dbe3ee;

        color: #172033;
    }

    .tabla-reservas tbody tr:hover {
        background: #f8fafc;
    }

    .id-reserva {
        font-weight: bold;
    }

    .fecha-texto {
        color: #64748b !important;
    }

    /* Estados */
    .estado {
        display: inline-block;

        padding: 5px 10px;

        border-radius: 6px;

        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .estado-confirmada {
        color: #047857;
        background: #e7f8f1;
    }

    .estado-pendiente {
        color: #b45309;
        background: #fff7df;
    }

    .estado-cancelada {
        color: #dc2626;
        background: #feecec;
    }

    .estado-finalizada {
        color: #475569;
        background: #edf1f5;
    }

    /* Pie de tabla */
    .tabla-footer {
        margin-top: 25px;

        display: flex;
        justify-content: space-between;
        align-items: center;

        color: #64748b;
        font-size: 14px;
    }

    .sin-resultados {
        text-align: center;
        padding: 35px !important;
        color: #64748b !important;
    }

    @media (max-width: 1100px) {
        .reservas-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .buscar {
            width: 200px;
        }
    }
</style>
@endpush


@section('content')

    <!-- ========================================
         FILTROS
    ========================================= -->

    <form method="GET"
          action="{{ route('reservas.index') }}">

        <div class="reservas-toolbar">

            <div class="filtros">

                <!-- Búsqueda por RUT -->
                <div class="input-group">

                    <i class="bi bi-search"></i>

                    <input
                        class="buscar"
                        type="text"
                        name="rut"
                        placeholder="Buscar por RUT..."
                        value="{{ request('rut') }}"
                    >

                </div>


                <!-- Estado -->

                <select
                    class="select-estado"
                    name="estado"
                >

                    <option value="">
                        Todos los estados
                    </option>

                    <option
                        value="Pendiente"
                        {{ request('estado') == 'Pendiente' ? 'selected' : '' }}
                    >
                        Pendiente
                    </option>

                    <option
                        value="Confirmada"
                        {{ request('estado') == 'Confirmada' ? 'selected' : '' }}
                    >
                        Confirmada
                    </option>

                    <option
                        value="Cancelada"
                        {{ request('estado') == 'Cancelada' ? 'selected' : '' }}
                    >
                        Cancelada
                    </option>

                    <option
                        value="Finalizada"
                        {{ request('estado') == 'Finalizada' ? 'selected' : '' }}
                    >
                        Finalizada
                    </option>

                </select>


                <!-- Fecha entrada -->

                <input
                    class="fecha"
                    type="date"
                    name="fecha_entrada"
                    value="{{ request('fecha_entrada') }}"
                    title="Fecha de entrada"
                >


                <!-- Fecha salida -->

                <input
                    class="fecha"
                    type="date"
                    name="fecha_salida"
                    value="{{ request('fecha_salida') }}"
                    title="Fecha de salida"
                >


                <button
                    class="btn-buscar"
                    type="submit"
                >
                    Buscar
                </button>


                <a
                    class="btn-limpiar"
                    href="{{ route('reservas.index') }}"
                >
                    Limpiar
                </a>

            </div>


            <!-- RF09: posteriormente conectaremos este botón -->

            <a href="{{ route('reservas.create') }}" class="btn-nueva">

                <i class="bi bi-plus-lg"></i>

                Nueva reserva

            </a>

        </div>

    </form>


    <!-- ========================================
         TABLA
    ========================================= -->

    <div class="tabla-container">

        <div class="tabla-responsive">

            <table class="tabla-reservas">

                <thead>

                    <tr>
                        <th>ID Reserva</th>
                        <th>Huésped</th>
                        <th>RUT</th>
                        <th>Habitación</th>
                        <th>Tipo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($reservas as $reserva)

                        <tr>

                            <td class="id-reserva">
                                #{{ $reserva->id_reserva }}
                            </td>


                            <td>
                                {{ $reserva->nombre }}
                                {{ $reserva->apellido }}
                            </td>


                            <td>
                                {{ $reserva->rut }}
                            </td>


                            <td>
                                Hab. {{ $reserva->habitacion }}
                            </td>


                            <td>
                                {{ $reserva->tipo }}
                            </td>


                            <td class="fecha-texto">
                                {{ date('d/m/Y', strtotime($reserva->fecha_entrada)) }}
                            </td>


                            <td class="fecha-texto">
                                {{ date('d/m/Y', strtotime($reserva->fecha_salida)) }}
                            </td>


                            <td>

                                @php
                                    $claseEstado = match($reserva->estado) {
                                        'Confirmada' => 'estado-confirmada',
                                        'Pendiente' => 'estado-pendiente',
                                        'Cancelada' => 'estado-cancelada',
                                        'Finalizada' => 'estado-finalizada',
                                        default => 'estado-finalizada'
                                    };
                                @endphp


                                <span class="estado {{ $claseEstado }}">

                                    {{ $reserva->estado }}

                                </span>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="sin-resultados"
                            >

                                No se encontraron reservas.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="tabla-footer">

            <span>

                {{ $reservas->count() }}

                @if ($reservas->count() == 1)
                    reserva encontrada
                @else
                    reservas encontradas
                @endif

            </span>

        </div>

    </div>

@endsection