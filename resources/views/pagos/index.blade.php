@extends('layouts.app')

@section('title', 'Pagos y Servicios - Gestión Hotel')
@section('page-title', 'Pagos y Servicios')
@section('menu-pagos', 'active')

@push('styles')
<style>
    .selector-card,
    .info-card,
    .panel {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
    }

    .selector-card {
        padding: 20px;
        margin-bottom: 20px;
    }

    .selector-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group.flex {
        flex: 1;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .form-control {
        width: 100%;
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

    .btn-primary {
        height: 42px;
        padding: 0 18px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .info-card {
        padding: 20px;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .info-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .info-value {
        color: #1e293b;
        font-size: 14px;
        font-weight: 600;
    }

    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .resumen-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 18px;
    }

    .resumen-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .resumen-valor {
        color: #1e293b;
        font-size: 22px;
        font-weight: 700;
    }

    .saldo-pendiente {
        color: #dc2626;
    }

    .saldo-pagado {
        color: #15803d;
    }

    .contenido-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .panel {
        padding: 20px;
    }

    .panel-header {
        margin-bottom: 18px;
    }

    .panel-title {
        margin: 0;
        color: #1e293b;
        font-size: 17px;
    }

    .panel-description {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .agregar-form {
        display: grid;
        grid-template-columns: 1fr 100px auto;
        gap: 10px;
        align-items: end;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pago-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 10px;
        align-items: end;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabla-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th {
        padding: 11px 8px;
        text-align: left;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 12px 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .monto {
        font-weight: 600;
    }

    .badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-pagado {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-pendiente {
        background: #fef3c7;
        color: #a16207;
    }

    .badge-anulado {
        background: #fee2e2;
        color: #b91c1c;
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

    .empty {
        padding: 25px;
        text-align: center;
        color: #64748b;
    }

    .sin-seleccion {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 60px 20px;
        text-align: center;
        color: #64748b;
    }

    .sin-seleccion i {
        display: block;
        margin-bottom: 15px;
        font-size: 36px;
        color: #94a3b8;
    }

    @media (max-width: 1000px) {
        .contenido-grid {
            grid-template-columns: 1fr;
        }

        .resumen-grid,
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .selector-form,
        .agregar-form,
        .pago-form {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .resumen-grid,
        .info-grid {
            grid-template-columns: 1fr;
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

<div class="selector-card">

    <form method="GET"
          action="{{ route('pagos.index') }}"
          class="selector-form">

        <div class="form-group flex">
            <label for="reserva">
                Seleccionar reserva
            </label>

            <select
                id="reserva"
                name="reserva"
                class="form-control"
                required
            >

                <option value="">
                    Seleccionar reserva...
                </option>

                @foreach ($reservas as $reserva)

                    <option
                        value="{{ $reserva->id_reserva }}"
                        {{ request('reserva') == $reserva->id_reserva ? 'selected' : '' }}
                    >
                        #{{ $reserva->id_reserva }}
                        - {{ $reserva->nombre }} {{ $reserva->apellido }}
                        - {{ $reserva->rut }}
                        - {{ $reserva->estado }}
                    </option>

                @endforeach

            </select>
        </div>

        <button type="submit" class="btn-primary">
            <i class="bi bi-search"></i>
            Consultar
        </button>

    </form>

</div>

@if ($reservaSeleccionada)

    <div class="info-card">

        <div class="info-grid">

            <div>
                <div class="info-label">Reserva</div>
                <div class="info-value">
                    #{{ $reservaSeleccionada->id_reserva }}
                </div>
            </div>

            <div>
                <div class="info-label">Huésped</div>
                <div class="info-value">
                    {{ $reservaSeleccionada->nombre }}
                    {{ $reservaSeleccionada->apellido }}
                </div>
            </div>

            <div>
                <div class="info-label">RUT</div>
                <div class="info-value">
                    {{ $reservaSeleccionada->rut }}
                </div>
            </div>

            <div>
                <div class="info-label">Estado</div>
                <div class="info-value">
                    {{ $reservaSeleccionada->estado }}
                </div>
            </div>

            <div>
                <div class="info-label">Entrada</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($reservaSeleccionada->fecha_entrada)->format('d/m/Y') }}
                </div>
            </div>

            <div>
                <div class="info-label">Salida</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($reservaSeleccionada->fecha_salida)->format('d/m/Y') }}
                </div>
            </div>

        </div>

    </div>

    <div class="resumen-grid">

        <div class="resumen-card">
            <div class="resumen-label">
                Alojamiento
            </div>

            <div class="resumen-valor">
                ${{ number_format($totalHabitacion, 0, ',', '.') }}
            </div>
        </div>

        <div class="resumen-card">
            <div class="resumen-label">
                Servicios
            </div>

            <div class="resumen-valor">
                ${{ number_format($totalServicios, 0, ',', '.') }}
            </div>
        </div>

        <div class="resumen-card">
            <div class="resumen-label">
                Total pagado
            </div>

            <div class="resumen-valor">
                ${{ number_format($totalPagado, 0, ',', '.') }}
            </div>
        </div>

        <div class="resumen-card">
            <div class="resumen-label">
                Saldo pendiente
            </div>

            <div class="resumen-valor {{ $saldo <= 0 ? 'saldo-pagado' : 'saldo-pendiente' }}">
                ${{ number_format($saldo, 0, ',', '.') }}
            </div>
        </div>

    </div>

    <div class="contenido-grid">

        <div class="panel">

            <div class="panel-header">
                <h2 class="panel-title">
                    Servicios y cargos
                </h2>

                <p class="panel-description">
                    Servicios consumidos durante la estadía.
                </p>
            </div>

            @if ($estadia && $estadia->estado === 'Activa')

                <form
                    method="POST"
                    action="{{ route('pagos.servicio') }}"
                    class="agregar-form"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="id_reserva"
                        value="{{ $reservaSeleccionada->id_reserva }}"
                    >

                    <div class="form-group">

                        <label>
                            Servicio
                        </label>

                        <select
                            name="id_servicio"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Seleccionar
                            </option>

                            @foreach ($servicios as $servicio)

                                <option value="{{ $servicio->id_servicio }}">
                                    {{ $servicio->nombre }}
                                    - ${{ number_format($servicio->precio, 0, ',', '.') }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="form-group">

                        <label>
                            Cantidad
                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            min="1"
                            value="1"
                            class="form-control"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Agregar
                    </button>

                </form>

            @endif

            <div class="tabla-responsive">

                <table>

                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Cant.</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($cargos as $cargo)

                            <tr>

                                <td>
                                    {{ $cargo->servicio }}
                                </td>

                                <td>
                                    {{ $cargo->cantidad }}
                                </td>

                                <td>
                                    ${{ number_format($cargo->precio, 0, ',', '.') }}
                                </td>

                                <td class="monto">
                                    ${{ number_format($cargo->subtotal, 0, ',', '.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    No hay servicios registrados.
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
                    Pagos
                </h2>

                <p class="panel-description">
                    Registra y consulta los pagos de la reserva.
                </p>
            </div>

            @if ($saldo > 0)

                <form
                    method="POST"
                    action="{{ route('pagos.registrar') }}"
                    class="pago-form"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="id_reserva"
                        value="{{ $reservaSeleccionada->id_reserva }}"
                    >

                    <div class="form-group">

                        <label>
                            Monto
                        </label>

                        <input
                            type="number"
                            name="monto"
                            min="1"
                            max="{{ max(0, $saldo) }}"
                            class="form-control"
                            value="{{ max(0, $saldo) }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label>
                            Método
                        </label>

                        <select
                            name="metodo_pago"
                            class="form-control"
                            required
                        >
                            <option value="Efectivo">
                                Efectivo
                            </option>

                            <option value="Tarjeta">
                                Tarjeta
                            </option>

                            <option value="Transferencia">
                                Transferencia
                            </option>
                        </select>

                    </div>

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Registrar
                    </button>

                </form>

            @else

                <div class="alert-success">
                    <i class="bi bi-check-circle"></i>
                    La cuenta se encuentra pagada.
                </div>

            @endif

            <div class="tabla-responsive">

                <table>

                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($pagos as $pago)

                            @php
                                $clasePago = match($pago->estado) {
                                    'Pagado' => 'badge-pagado',
                                    'Pendiente' => 'badge-pendiente',
                                    default => 'badge-anulado'
                                };
                            @endphp

                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    {{ $pago->metodo_pago }}
                                </td>

                                <td class="monto">
                                    ${{ number_format($pago->monto, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge {{ $clasePago }}">
                                        {{ $pago->estado }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    No hay pagos registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@else

    <div class="sin-seleccion">

        <i class="bi bi-receipt"></i>

        Selecciona una reserva para consultar su cuenta,
        servicios y pagos.

    </div>

@endif

@endsection