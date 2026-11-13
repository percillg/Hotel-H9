@extends('layouts.app')

@section('title', 'Check-in / Check-out - HotelH9')
@section('page-title', 'Check-in / Check-out')
@section('menu-checkin', 'active')

@push('styles')
<style>
    .estadias-toolbar {
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
        width: 360px;
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

    .tabla-estadias {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .tabla-estadias thead {
        background: #f6f8fb;
    }

    .tabla-estadias th {
        padding: 13px;
        text-align: left;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        border-bottom: 1px solid #dbe3ee;
    }

    .tabla-estadias td {
        padding: 14px 13px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .tabla-estadias tbody tr:hover {
        background: #f8fafc;
    }

    .nombre-huesped {
        font-weight: 600;
        color: #1e293b;
    }

    .rut {
        margin-top: 3px;
        color: #64748b;
        font-size: 12px;
    }

    .habitacion {
        font-weight: 600;
    }

    .tipo {
        margin-top: 3px;
        color: #64748b;
        font-size: 12px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pendiente {
        background: #fff7db;
        color: #a16207;
    }

    .badge-activa {
        background: #dcfce7;
        color: #15803d;
    }

    .fecha-operacion {
        display: block;
        box-sizing: border-box;
        width: 160px;
        height: 34px;
        margin-bottom: 8px;
        padding: 5px 8px;
        border: 1px solid #d7dfeb;
        border-radius: 6px;
        background: white;
        color: #172033;
    }

    .etiqueta-fecha {
        display: block;
        margin-bottom: 5px;
        font-size: 12px;
        color: #475569;
    }

    .btn-checkin {
        border: none;
        border-radius: 6px;
        background: #2563eb;
        color: white;
        padding: 8px 13px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-checkout {
        border: none;
        border-radius: 6px;
        background: #dc2626;
        color: white;
        padding: 8px 13px;
        font-size: 13px;
        font-weight: 600;
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

    .resumen {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .resumen-card {
        flex: 1;
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 18px;
    }

    .resumen-label {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .resumen-numero {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    @media (max-width: 850px) {
        .estadias-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .input-busqueda input {
            width: 100%;
        }

        .resumen {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

@php
    $fechaMinima = now('America/Santiago')->subDays(7)->toDateString();
    $fechaMaxima = now('America/Santiago')->toDateString();
@endphp

<p style="margin-bottom: 18px; color: #E9E6E7; font-size: 13px;">
    El check-in admite fechas desde hace 7 días hasta hoy. El check-out solo permite fechas posteriores al check-in. Si eliges una fecha futura, se programa la salida sin finalizar la estadía.
</p>

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

@php
    $pendientes = $reservas->filter(function ($reserva) {
        return !$reserva->id_estadia;
    })->count();

    $activas = $reservas->filter(function ($reserva) {
        return $reserva->estado_estadia === 'Activa';
    })->count();
@endphp

<div class="resumen">

    <div class="resumen-card">
        <div class="resumen-label">
            Check-in pendientes
        </div>

        <div class="resumen-numero">
            {{ $pendientes }}
        </div>
    </div>

    <div class="resumen-card">
        <div class="resumen-label">
            Estadías activas
        </div>

        <div class="resumen-numero">
            {{ $activas }}
        </div>
    </div>

</div>

<div class="estadias-toolbar">

    <form method="GET"
          action="{{ route('estadias.index') }}"
          class="busqueda-form">

        <div class="input-busqueda">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Buscar huésped, RUT, reserva o habitación..."
            >

        </div>

        <button type="submit"
                class="btn-buscar">
            Buscar
        </button>

        @if (request('buscar'))

            <a href="{{ route('estadias.index') }}"
               class="btn-limpiar">
                Limpiar
            </a>

        @endif

    </form>

</div>

<div class="tabla-container">

    <div class="tabla-responsive">

        <table class="tabla-estadias">

            <thead>
                <tr>
                    <th>Reserva</th>
                    <th>Huésped</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($reservas as $reserva)

                    <tr>

                        <td>
                            #{{ $reserva->id_reserva }}
                        </td>

                        <td>
                            <div class="nombre-huesped">
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

                            @if ($reserva->estado_estadia === 'Activa')

                                <span class="badge badge-activa">
                                    Estadía activa
                                </span>

                            @else

                                <span class="badge badge-pendiente">
                                    Check-in pendiente
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($reserva->estado_estadia === 'Activa')

                                <form
                                    method="POST"
                                    action="{{ route('estadias.checkout', $reserva->id_reserva) }}"
                                    onsubmit="return confirm('Si la fecha es futura, se programará la salida. Si es hoy o anterior, se finalizará la estadía. ¿Deseas continuar?')"
                                >

                                    @csrf

                                    @php
                                        $diaCheckin = $reserva->fecha_checkin
                                            ? \Carbon\Carbon::parse($reserva->fecha_checkin, 'UTC')->setTimezone('America/Santiago')->toDateString()
                                            : $fechaMaxima;
                                    @endphp

                                    @php
                                        $fechaSalidaMinima = \Carbon\Carbon::parse($diaCheckin)->addDay()->toDateString();
                                        $fechaSalidaSugerida = max($fechaSalidaMinima, $fechaMaxima);
                                    @endphp

                                    <label class="etiqueta-fecha" for="fecha_checkout_{{ $reserva->id_reserva }}">
                                        Fecha de salida
                                    </label>
                                    <input
                                        id="fecha_checkout_{{ $reserva->id_reserva }}"
                                        type="date"
                                        name="fecha_checkout"
                                        class="fecha-operacion"
                                        min="{{ $fechaSalidaMinima }}"
                                        value="{{ $fechaSalidaSugerida }}"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="btn-checkout"
                                    >
                                        <i class="bi bi-box-arrow-right"></i>
                                        Confirmar fecha de salida
                                    </button>

                                </form>

                            @else

                                <form
                                    method="POST"
                                    action="{{ route('estadias.checkin', $reserva->id_reserva) }}"
                                    onsubmit="return confirm('¿Confirmar check-in del huésped?')"
                                >

                                    @csrf

                                    <label class="etiqueta-fecha" for="fecha_checkin_{{ $reserva->id_reserva }}">
                                        Fecha de check-in
                                    </label>
                                    <input
                                        id="fecha_checkin_{{ $reserva->id_reserva }}"
                                        type="date"
                                        name="fecha_checkin"
                                        class="fecha-operacion"
                                        min="{{ $fechaMinima }}"
                                        max="{{ $fechaMaxima }}"
                                        value="{{ $fechaMaxima }}"
                                        required
                                    >

                                    <button
                                        type="submit"
                                        class="btn-checkin"
                                    >
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Check-in
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="sin-resultados">

                            No hay reservas disponibles para check-in o check-out.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
