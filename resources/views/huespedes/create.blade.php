@extends('layouts.app')

@section('title', 'Nuevo Huésped - Gestión Hotel')
@section('page-title', 'Nuevo Huésped')
@section('menu-huespedes', 'active')

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
        border: 0;
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
        font-size: 14px;
    }

    .campo-error {
        color: #dc2626;
        font-size: 12px;
    }

    @media (max-width: 700px) {
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

<div class="form-container">

    <h2 class="form-title">
        Registrar nuevo huésped
    </h2>

    <p class="form-description">
        Ingresa los datos personales y de contacto del huésped.
    </p>

    @if ($errors->any())

        <div class="alert-error">
            <strong>Revisa los datos ingresados.</strong>
        </div>

    @endif

    <form method="POST"
          action="{{ route('huespedes.store') }}">

        @csrf

        <div class="form-grid">

            <div class="form-group">

                <label for="nombre">
                    Nombre *
                </label>

                <input
                    id="nombre"
                    type="text"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre') }}"
                    required
                >

                @error('nombre')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="apellido">
                    Apellido *
                </label>

                <input
                    id="apellido"
                    type="text"
                    name="apellido"
                    class="form-control"
                    value="{{ old('apellido') }}"
                    required
                >

                @error('apellido')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="rut">
                    RUT *
                </label>

                <input
                    id="rut"
                    type="text"
                    name="rut"
                    class="form-control"
                    value="{{ old('rut') }}"
                    placeholder="12.345.678-9"
                    required
                >

                @error('rut')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="telefono">
                    Teléfono
                </label>

                <input
                    id="telefono"
                    type="text"
                    name="telefono"
                    class="form-control"
                    value="{{ old('telefono') }}"
                    placeholder="+56 9 1234 5678"
                >

                @error('telefono')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group full">

                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.cl"
                >

                @error('email')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group full">

                <label for="direccion">
                    Dirección
                </label>

                <input
                    id="direccion"
                    type="text"
                    name="direccion"
                    class="form-control"
                    value="{{ old('direccion') }}"
                >

                @error('direccion')
                    <span class="campo-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

        </div>

        <div class="form-actions">

            <a href="{{ route('huespedes.index') }}"
               class="btn-cancelar">
                Cancelar
            </a>

            <button type="submit"
                    class="btn-guardar">

                <i class="bi bi-check-lg"></i>
                Registrar huésped

            </button>

        </div>

    </form>

</div>

@endsection