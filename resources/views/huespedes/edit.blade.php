@extends('layouts.app')

@section('title', 'Editar huésped - HotelH9')
@section('page-title', 'Editar huésped')
@section('menu-huespedes', 'active')

@push('styles')
<style>
    .edit-card { max-width: 900px; background: #F8F6F5; border: 1px solid #dbe3ee; border-radius: 12px; padding: 28px; }
    .edit-card h2 { margin: 0 0 6px; font-size: 20px; }
    .edit-description { color: #64748b; margin: 0 0 24px; font-size: 14px; }
    .edit-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .edit-field { display: flex; flex-direction: column; gap: 7px; min-width: 0; }
    .edit-field.full { grid-column: 1 / -1; }
    .edit-field label { font-size: 13px; font-weight: 600; color: #475569; }
    .edit-control { height: 44px; width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 12px; background: white; font: inherit; font-size: 14px; }
    .edit-control:focus { border-color: #2563eb; outline: 2px solid #bfdbfe; }
    .edit-error { color: #b91c1c; font-size: 12px; }
    .edit-errors { border: 1px solid #fecaca; background: #fef2f2; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; color: #991b1b; }
    .edit-errors ul { margin-bottom: 0; padding-left: 20px; }
    .edit-actions { display: flex; justify-content: flex-end; flex-wrap: wrap; gap: 10px; padding-top: 20px; margin-top: 24px; border-top: 1px solid #e2e8f0; }
    .edit-actions a, .edit-actions button { min-height: 44px; padding: 11px 18px; border-radius: 8px; font: inherit; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; }
    .edit-back { color: #475569; background: white; border: 1px solid #cbd5e1; }
    .edit-save { background: #4F4845; color: white; border: 1px solid #4F4845; }
    @media (max-width: 700px) { .edit-card { padding: 18px; } .edit-grid { grid-template-columns: 1fr; } .edit-field.full { grid-column: auto; } .edit-actions a, .edit-actions button { flex: 1 1 145px; } }
</style>
@endpush

@section('content')
<div class="edit-card">
    <h2>Modificar datos del huésped</h2>
    <p class="edit-description">Actualiza la ficha. Si cambias el nombre o RUT, también se actualizarán sus referencias en las reservas.</p>

    @if ($errors->any())
        <div class="edit-errors" role="alert">
            <strong>Revisa la información:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('huespedes.update', $huesped->_id) }}">
        @csrf
        @method('PUT')
        <div class="edit-grid">
            <div class="edit-field">
                <label for="nombre">Nombre *</label>
                <input id="nombre" name="nombre" class="edit-control" maxlength="50" required value="{{ old('nombre', $huesped->nombre) }}">
                @error('nombre') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
            <div class="edit-field">
                <label for="apellido">Apellido *</label>
                <input id="apellido" name="apellido" class="edit-control" maxlength="50" required value="{{ old('apellido', $huesped->apellido) }}">
                @error('apellido') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
            <div class="edit-field">
                <label for="rut">RUT *</label>
                <input id="rut" name="rut" class="edit-control" maxlength="20" required value="{{ old('rut', $huesped->rut) }}">
                @error('rut') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
            <div class="edit-field">
                <label for="telefono">Teléfono</label>
                <input id="telefono" name="telefono" class="edit-control" maxlength="20" inputmode="tel" value="{{ old('telefono', $huesped->telefono ?? '') }}">
                @error('telefono') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
            <div class="edit-field full">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" class="edit-control" maxlength="100" value="{{ old('email', $huesped->email ?? '') }}">
                @error('email') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
            <div class="edit-field full">
                <label for="direccion">Dirección</label>
                <input id="direccion" name="direccion" class="edit-control" maxlength="150" value="{{ old('direccion', $huesped->direccion ?? '') }}">
                @error('direccion') <span class="edit-error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="edit-actions">
            <a class="edit-back" href="{{ route('huespedes.index') }}">Cancelar</a>
            <button class="edit-save" type="submit"><i class="bi bi-check-lg" aria-hidden="true"></i> Guardar cambios</button>
        </div>
    </form>
</div>
@endsection
