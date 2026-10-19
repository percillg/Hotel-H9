@extends('layouts.app')

@section('title', 'Dashboard - Gestión Hotel')
@section('page-title', 'Dashboard')
@section('menu-dashboard', 'active')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
    }

    .stat-value {
        color: #1e293b;
        font-size: 27px;
        font-weight: 700;
    }

    .stat-detail {
        margin-top: 7px;
        color: #64748b;
        font-size: 12px;
    }

    .dashboard-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .panel {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 20px;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .panel-title {
        margin: 0;
        font-size: 17px;
        color: #1e293b;
    }

    .panel-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .tabla-responsive {
        overflow-x: auto;
    }

    .tabla-dashboard {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .tabla-dashboard th {
        padding: 11px;
        text-align: left;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabla-dashboard td {
        padding: 13px 11px;
        border-bottom: 1px solid #e2e8f0;
    }

    .huesped {
        font-weight: 600;
        color: #1e293b;
    }

    .habitacion {
        font-weight: 600;
    }

    .estado-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .estado-item:last-child {
        border-bottom: none;
    }

    .estado-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .estado-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .estado-disponible {
        background: #dcfce7;
        color: #15803d;
    }

    .estado-ocupada {
        background: #fee2e2;
        color: #b91c1c;
    }

    .estado-limpieza {
        background: #fef3c7;
        color: #a16207;
    }

    .estado-nombre {
        color: #475569;
        font-size: 13px;
    }

    .estado-numero {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }

    .ocupacion-container {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .ocupacion-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        color: #64748b;
    }

    .ocupacion-porcentaje {
        color: #1e293b;
        font-weight: 700;
    }

    .barra {
        width: 100%;
        height: 8px;
        background: #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
    }

    .barra-progreso {
        height: 100%;
        background: #2563eb;
        border-radius: 20px;
    }

    .empty {
        padding: 30px !important;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 1100px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="dashboard-grid">

    <div class="stat-card">

        <div class="stat-header">
            <div class="stat-label">
                Ocupación
            </div>

            <div class="stat-icon">
                <i class="bi bi-door-open"></i>
            </div>
        </div>

        <div class="stat-value">
            {{ $ocupacion }}%
        </div>

        <div class="stat-detail">
            {{ $habitacionesOcupadas }} de {{ $totalHabitaciones }} habitaciones
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-header">
            <div class="stat-label">
                Reservas confirmadas
            </div>

            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>

        <div class="stat-value">
            {{ $reservasConfirmadas }}
        </div>

        <div class="stat-detail">
            Reservas actualmente confirmadas
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-header">
            <div class="stat-label">
                Huéspedes
            </div>

            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </div>

        <div class="stat-value">
            {{ $huespedesRegistrados }}
        </div>

        <div class="stat-detail">
            Huéspedes registrados
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-header">
            <div class="stat-label">
                Ingresos registrados
            </div>

            <div class="stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>

        <div class="stat-value">
            ${{ number_format($ingresos, 0, ',', '.') }}
        </div>

        <div class="stat-detail">
            Total de pagos registrados
        </div>

    </div>

</div>

<div class="dashboard-content">

    <div class="panel">

        <div class="panel-header">

            <h2 class="panel-title">
                Próximas reservas
            </h2>

            <a href="{{ route('reservas.index') }}"
               class="panel-link">
                Ver todas
            </a>

        </div>

        <div class="tabla-responsive">

            <table class="tabla-dashboard">

                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Huésped</th>
                        <th>Habitación</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($proximasReservas as $reserva)

                        <tr>

                            <td>
                                #{{ $reserva->id_reserva }}
                            </td>

                            <td class="huesped">
                                {{ $reserva->nombre }}
                                {{ $reserva->apellido }}
                            </td>

                            <td class="habitacion">
                                Hab. {{ $reserva->habitacion }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fecha_salida)->format('d/m/Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty">
                                No hay reservas confirmadas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="panel">

        <div class="panel-header">

            <h2 class="panel-title">
                Estado de habitaciones
            </h2>

            <a href="{{ route('habitaciones.index') }}"
               class="panel-link">
                Ver habitaciones
            </a>

        </div>

        <div class="estado-item">

            <div class="estado-info">

                <div class="estado-icon estado-disponible">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="estado-nombre">
                    Disponibles
                </div>

            </div>

            <div class="estado-numero">
                {{ $habitacionesDisponibles }}
            </div>

        </div>

        <div class="estado-item">

            <div class="estado-info">

                <div class="estado-icon estado-ocupada">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div class="estado-nombre">
                    Ocupadas
                </div>

            </div>

            <div class="estado-numero">
                {{ $habitacionesOcupadas }}
            </div>

        </div>

        <div class="estado-item">

            <div class="estado-info">

                <div class="estado-icon estado-limpieza">
                    <i class="bi bi-stars"></i>
                </div>

                <div class="estado-nombre">
                    En limpieza
                </div>

            </div>

            <div class="estado-numero">
                {{ $habitacionesLimpieza }}
            </div>

        </div>

        <div class="ocupacion-container">

            <div class="ocupacion-header">

                <span>
                    Ocupación actual
                </span>

                <span class="ocupacion-porcentaje">
                    {{ $ocupacion }}%
                </span>

            </div>

            <div class="barra">

                <div
                    class="barra-progreso"
                    style="width: {{ min(100, max(0, $ocupacion)) }}%"
                ></div>

            </div>

        </div>

    </div>

</div>

@endsection