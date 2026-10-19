@extends('layouts.app')

@section('title', 'Nueva Reserva - HotelH9')
@section('page-title', 'Nueva Reserva')
@section('menu-reservas', 'active')

@push('styles')
<style>
    .form-container {
        max-width: 900px;
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 28px;
    }

    .form-title {
        margin: 0 0 5px;
        font-size: 18px;
    }

    .form-description {
        margin: 0 0 25px;
        color: #64748b;
        font-size: 14px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group.full {
        grid-column: 1 / -1;
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
        color: #172033;
        outline: none;
    }

    .form-control:focus {
        border-color: #2563eb;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-cancelar {
        padding: 10px 18px;
        border: 1px solid #d7dfeb;
        border-radius: 7px;
        background: white;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-guardar {
        padding: 10px 20px;
        border: 0;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-guardar:hover {
        background: #1d4ed8;
    }

    .info {
        margin-top: 7px;
        color: #64748b;
        font-size: 12px;
    }

    .alert-error {
        margin-bottom: 20px;
        padding: 14px;
        border-radius: 7px;
        background: #feecec;
        color: #b91c1c;
        font-size: 14px;
    }

    @media (max-width: 750px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }
    }
</style>
@endpush


@section('content')

@php
    $fechaMinima = now('America/Santiago')->subDays(7)->toDateString();
@endphp

<div class="form-container">

    <h2 class="form-title">
        Registrar nueva reserva
    </h2>

    <p class="form-description">
        Ingresa los datos necesarios para crear una nueva reserva. Las fechas no pueden ser anteriores a 7 días atrás.
    </p>


    @if ($errors->any())

        <div class="alert-error">

            <strong>No se pudo registrar la reserva.</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('reservas.store') }}">

        @csrf

        <div class="form-grid">

            <!-- HUÉSPED -->

            <div class="form-group full">

                <label for="id_huesped">
                    Huésped
                </label>

                <select
                    id="id_huesped"
                    name="id_huesped"
                    class="form-control"
                    required
                >

                    <option value="">
                        Seleccione un huésped
                    </option>

                    @foreach ($huespedes as $huesped)

                        <option
                            value="{{ $huesped->id_huesped }}"
                            {{ old('id_huesped') == $huesped->id_huesped ? 'selected' : '' }}
                        >

                            {{ $huesped->nombre }}
                            {{ $huesped->apellido }}
                            - {{ $huesped->rut }}

                        </option>

                    @endforeach

                </select>

            </div>


            <!-- FECHA ENTRADA -->

            <div class="form-group">

                <label for="fecha_entrada">
                    Fecha de entrada
                </label>

                <input
                    id="fecha_entrada"
                    type="date"
                    name="fecha_entrada"
                    class="form-control"
                    min="{{ $fechaMinima }}"
                    value="{{ old('fecha_entrada') }}"
                    required
                >

            </div>


            <!-- FECHA SALIDA -->

            <div class="form-group">

                <label for="fecha_salida">
                    Fecha de salida
                </label>

                <input
                    id="fecha_salida"
                    type="date"
                    name="fecha_salida"
                    class="form-control"
                    min="{{ $fechaMinima }}"
                    value="{{ old('fecha_salida') }}"
                    required
                >

            </div>


            <!-- HABITACIÓN -->

            <div class="form-group full">

                <label for="id_habitacion">
                    Habitación
                </label>

                <select
                    id="id_habitacion"
                    name="id_habitacion"
                    class="form-control"
                    required
                >

                    <option value="">
                        Seleccione una habitación
                    </option>

                    @foreach ($habitaciones as $habitacion)

                        <option
                            value="{{ $habitacion->id_habitacion }}"
                            {{ old('id_habitacion') == $habitacion->id_habitacion ? 'selected' : '' }}
                        >

                            Hab. {{ $habitacion->numero }}
                            -
                            {{ $habitacion->tipo }}
                            -
                            ${{ number_format($habitacion->precio, 0, ',', '.') }}

                        </option>

                    @endforeach

                </select>

                <span class="info">
                    Solo se muestran habitaciones actualmente disponibles.
                </span>

            </div>

        </div>


        <div class="form-actions">

            <a
                href="{{ route('reservas.index') }}"
                class="btn-cancelar"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn-guardar"
            >
                <i class="bi bi-check-lg"></i>
                Crear reserva
            </button>

        </div>

    </form>

</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const entrada = document.getElementById('fecha_entrada');
    const salida = document.getElementById('fecha_salida');
    const fechaBase = @json($fechaMinima);

    const actualizarSalida = () => {
        if (!entrada.value) {
            salida.min = fechaBase;
            return;
        }

        const fecha = new Date(`${entrada.value}T12:00:00`);
        fecha.setDate(fecha.getDate() + 1);
        const siguiente = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;
        salida.min = siguiente;

        if (salida.value && salida.value < siguiente) {
            salida.value = '';
        }
    };

    entrada.addEventListener('change', actualizarSalida);
    actualizarSalida();
});
</script>
@endpush
