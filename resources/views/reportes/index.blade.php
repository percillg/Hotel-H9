@extends('layouts.app')

@section('title', 'Reportes - Gestión Hotel')
@section('page-title', 'Reportes')
@section('menu-reportes', 'active')

@push('styles')
<style>
    .filtros-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .filtros-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .form-control {
        height: 42px;
        padding: 0 12px;
        border: 1px solid #d7dfeb;
        border-radius: 7px;
        background: white;
        outline: none;
    }

    .form-control:focus {
        border-color: #2563eb;
    }

    .btn-filtrar {
        height: 42px;
        padding: 0 18px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-limpiar {
        height: 42px;
        display: inline-flex;
        align-items: center;
        padding: 0 18px;
        border: 1px solid #d7dfeb;
        border-radius: 7px;
        background: white;
        color: #475569;
        text-decoration: none;
    }

    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .resumen-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
    }

    .resumen-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .resumen-label {
        color: #64748b;
        font-size: 13px;
    }

    .resumen-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 18px;
    }

    .resumen-value {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
    }

    .tabla-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
    }

    .tabla-header {
        margin-bottom: 18px;
    }

    .tabla-title {
        margin: 0;
        font-size: 17px;
        color: #1e293b;
    }

    .tabla-description {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .tabla-responsive {
        overflow-x: auto;
    }

    .tabla-reportes {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .tabla-reportes th {
        padding: 12px;
        text-align: left;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabla-reportes td {
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .huesped {
        font-weight: 600;
        color: #1e293b;
    }

    .rut {
        margin-top: 3px;
        color: #64748b;
        font-size: 11px;
    }

    .habitacion {
        font-weight: 600;
    }

    .tipo {
        margin-top: 3px;
        color: #64748b;
        font-size: 11px;
    }

    .badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-confirmada {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-pendiente {
        background: #fef3c7;
        color: #a16207;
    }

    .badge-finalizada {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-cancelada {
        background: #fee2e2;
        color: #b91c1c;
    }

    .empty {
        padding: 40px !important;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 1000px) {
        .resumen-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 700px) {
        .filtros-form {
            flex-direction: column;
            align-items: stretch;
        }

        .resumen-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="filtros-card">

    <form
        method="GET"
        action="{{ route('reportes.index') }}"
        class="filtros-form"
    >

        <div class="form-group">

            <label for="fecha_inicio">
                Desde
            </label>

            <input
                id="fecha_inicio"
                type="date"
                name="fecha_inicio"
                class="form-control"
                value="{{ $fechaInicio }}"
            >

        </div>

        <div class="form-group">

            <label for="fecha_fin">
                Hasta
            </label>

            <input
                id="fecha_fin"
                type="date"
                name="fecha_fin"
                class="form-control"
                value="{{ $fechaFin }}"
            >

        </div>

        <button
            type="submit"
            class="btn-filtrar"
        >
            <i class="bi bi-funnel"></i>
            Generar reporte
        </button>

        @if ($fechaInicio || $fechaFin)

            <a
                href="{{ route('reportes.index') }}"
                class="btn-limpiar"
            >
                Limpiar
            </a>

        @endif

    </form>

</div>

<div class="resumen-grid">

    <div class="resumen-card">

        <div class="resumen-header">

            <div class="resumen-label">
                Reservas
            </div>

            <div class="resumen-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

        </div>

        <div class="resumen-value">
            {{ $totalReservas }}
        </div>

    </div>

    <div class="resumen-card">

        <div class="resumen-header">

            <div class="resumen-label">
                Huéspedes
            </div>

            <div class="resumen-icon">
                <i class="bi bi-people"></i>
            </div>

        </div>

        <div class="resumen-value">
            {{ $huespedes }}
        </div>

    </div>

    <div class="resumen-card">

        <div class="resumen-header">

            <div class="resumen-label">
                Estadías finalizadas
            </div>

            <div class="resumen-icon">
                <i class="bi bi-check-circle"></i>
            </div>

        </div>

        <div class="resumen-value">
            {{ $reservasFinalizadas }}
        </div>

    </div>

    <div class="resumen-card">

        <div class="resumen-header">

            <div class="resumen-label">
                Ingresos
            </div>

            <div class="resumen-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

        </div>

        <div class="resumen-value">
            ${{ number_format($ingresos, 0, ',', '.') }}
        </div>

    </div>

</div>

<div class="tabla-card">

    <div class="tabla-header">

        <h2 class="tabla-title">
            Detalle de reservas
        </h2>

        <p class="tabla-description">

            @if ($fechaInicio && $fechaFin)

                Reservas entre
                {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}
                y
                {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}

            @elseif ($fechaInicio)

                Reservas desde
                {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}

            @elseif ($fechaFin)

                Reservas hasta
                {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}

            @else

                Todas las reservas registradas

            @endif

        </p>

    </div>

    <div class="tabla-responsive">

        <table class="tabla-reportes">

            <thead>

                <tr>
                    <th>Reserva</th>
                    <th>Huésped</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($reservas as $reserva)

                    @php
                        $claseEstado = match($reserva->estado) {
                            'Confirmada' => 'badge-confirmada',
                            'Pendiente' => 'badge-pendiente',
                            'Finalizada' => 'badge-finalizada',
                            default => 'badge-cancelada'
                        };
                    @endphp

                    <tr>

                        <td>
                            #{{ $reserva->id_reserva }}
                        </td>

                        <td>

                            <div class="huesped">
                                {{ $reserva->nombre }}
                                {{ $reserva->apellido }}
                            </div>

                            <div class="rut">
                                {{ $reserva->rut }}
                            </div>

                        </td>

                        <td>

                            <div class="habitacion">
                                Hab. {{ $reserva->habitacion }}
                            </div>

                            <div class="tipo">
                                {{ $reserva->tipo }}
                            </div>

                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}
                        </td>

                        <td>

                            <span class="badge {{ $claseEstado }}">
                                {{ $reserva->estado }}
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty">
                            No se encontraron reservas para el período seleccionado.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection