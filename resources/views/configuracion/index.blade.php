@extends('layouts.app')

@section('title', 'Configuración - Gestión Hotel')
@section('page-title', 'Configuración')
@section('menu-configuracion', 'active')

@push('styles')
<style>
    .config-container {
        max-width: 850px;
    }

    .config-card {
        background: white;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .config-header {
        padding: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .config-header h2 {
        margin: 0;
        color: #1e293b;
        font-size: 17px;
    }

    .config-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .config-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #d7dfeb;
        border-radius: 7px;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #2563eb;
    }

    .btn-guardar {
        height: 42px;
        padding: 0 18px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .alert-success {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 7px;
        background: #dcfce7;
        color: #166534;
        font-size: 13px;
    }

    .alert-error {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 7px;
        background: #fee2e2;
        color: #991b1b;
        font-size: 13px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        font-size: 13px;
    }

    .info-value {
        color: #1e293b;
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<div class="config-container">

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="config-card">

        <div class="config-header">
            <h2>Información del hotel</h2>
            <p>Configura la información general utilizada por el sistema.</p>
        </div>

        <div class="config-body">

            <form method="POST" action="{{ route('configuracion.actualizar') }}">

                @csrf

                <div class="form-group">

                    <label for="nombre_hotel">
                        Nombre del hotel
                    </label>

                    <input
                        id="nombre_hotel"
                        type="text"
                        name="nombre_hotel"
                        class="form-control"
                        value="{{ old('nombre_hotel', $nombreHotel) }}"
                        required
                    >

                </div>

                <button type="submit" class="btn-guardar">
                    <i class="bi bi-check-lg"></i>
                    Guardar cambios
                </button>

            </form>

        </div>

    </div>

    <div class="config-card">

        <div class="config-header">
            <h2>Información del sistema</h2>
            <p>Información técnica de la aplicación.</p>
        </div>

        <div class="config-body">

            <div class="info-row">
                <span class="info-label">Sistema</span>
                <span class="info-value">Gestión de Hotel</span>
            </div>

            <div class="info-row">
                <span class="info-label">Framework</span>
                <span class="info-value">Laravel</span>
            </div>

            <div class="info-row">
                <span class="info-label">Base de datos</span>
                <span class="info-value">MongoDB</span>
            </div>

            <div class="info-row">
                <span class="info-label">Servidor de base de datos</span>
                <span class="info-value">MongoDB Atlas</span>
            </div>

        </div>

    </div>

</div>

@endsection