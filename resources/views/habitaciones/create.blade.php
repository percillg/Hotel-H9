@extends('layouts.app')

@section('title', 'Nueva Habitación - Gestión Hotel')
@section('page-title', 'Nueva Habitación')
@section('menu-habitaciones', 'active')

@push('styles')
<style>
    .form-container {
        max-width: 850px;
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 28px;
    }

    .form-title {
        margin: 0 0 5px;
        font-size: 18px;
        color: #1e293b;
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
    }

    .btn-guardar {
        padding: 10px 20px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .alert-error {
        margin-bottom: 20px;
        padding: 14px 18px;
        border-radius: 7px;
        background: #feecec;
        color: #b91c1c;
    }

    .campo-error {
        color: #dc2626;
        font-size: 12px;
    }

    @media (max-width: 700px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="form-container">

    <h2 class="form-title">
        Registrar nueva habitación
    </h2>

    <p class="form-description">
        Ingresa la información de la habitación.
    </p>

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('habitaciones.store') }}"
    >

        @csrf

        <div class="form-grid">

            <div class="form-group">

                <label for="numero">
                    Número de habitación *
                </label>

                <input
                    id="numero"
                    type="number"
                    name="numero"
                    class="form-control"
                    value="{{ old('numero') }}"
                    required
                >

                @error('numero')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="tipo">
                    Tipo *
                </label>

                <select
                    id="tipo"
                    name="tipo"
                    class="form-control"
                    required
                >
                    <option value="">
                        Seleccionar tipo
                    </option>

                    <option value="Individual"
                        {{ old('tipo') === 'Individual' ? 'selected' : '' }}>
                        Individual
                    </option>

                    <option value="Doble"
                        {{ old('tipo') === 'Doble' ? 'selected' : '' }}>
                        Doble
                    </option>

                    <option value="Doble Superior"
                        {{ old('tipo') === 'Doble Superior' ? 'selected' : '' }}>
                        Doble Superior
                    </option>

                    <option value="Suite"
                        {{ old('tipo') === 'Suite' ? 'selected' : '' }}>
                        Suite
                    </option>

                </select>

                @error('tipo')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="capacidad">
                    Capacidad *
                </label>

                <input
                    id="capacidad"
                    type="number"
                    name="capacidad"
                    min="1"
                    class="form-control"
                    value="{{ old('capacidad') }}"
                    required
                >

                @error('capacidad')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="precio">
                    Precio por noche *
                </label>

                <input
                    id="precio"
                    type="number"
                    name="precio"
                    min="0"
                    step="1"
                    class="form-control"
                    value="{{ old('precio') }}"
                    placeholder="60000"
                    required
                >

                @error('precio')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="estado">
                    Estado inicial *
                </label>

                <select
                    id="estado"
                    name="estado"
                    class="form-control"
                    required
                >

                    <option value="Disponible"
                        {{ old('estado', 'Disponible') === 'Disponible' ? 'selected' : '' }}>
                        Disponible
                    </option>

                    <option value="En limpieza"
                        {{ old('estado') === 'En limpieza' ? 'selected' : '' }}>
                        En limpieza
                    </option>

                    <option value="Fuera de servicio"
                        {{ old('estado') === 'Fuera de servicio' ? 'selected' : '' }}>
                        Fuera de servicio
                    </option>

                </select>

            </div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('habitaciones.index') }}"
                class="btn-cancelar"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn-guardar"
            >
                <i class="bi bi-check-lg"></i>
                Registrar habitación
            </button>

        </div>

    </form>

</div>

@endsection