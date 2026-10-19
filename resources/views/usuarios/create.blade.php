@extends('layouts.app')
@section('title', 'Crear usuario - Gestión Hotel')
@section('page-title', 'Nuevo usuario')
@section('menu-usuarios', 'active')
@section('content')
    <div class="card" style="max-width:750px;">
        <h2>Registrar personal</h2>
        @if ($errors->any())
            <p style="color:#b91c1c;" role="alert">{{ $errors->first() }}</p>
        @endif
        <form action="{{ route('usuarios.store') }}" method="POST" style="display:grid;gap:15px;">
            @csrf
            <label>Nombre<input name="nombre" value="{{ old('nombre') }}" required maxlength="50" style="display:block;width:100%;padding:11px;margin-top:7px;"></label>
            <label>Apellido<input name="apellido" value="{{ old('apellido') }}" required maxlength="50" style="display:block;width:100%;padding:11px;margin-top:7px;"></label>
            <label>Nombre de usuario<input name="nombre_usuario" value="{{ old('nombre_usuario') }}" required maxlength="50" style="display:block;width:100%;padding:11px;margin-top:7px;"></label>
            <label>Contraseña<input name="password" type="password" required minlength="10" style="display:block;width:100%;padding:11px;margin-top:7px;"></label>
            <label>Rol<select name="rol" required style="display:block;width:100%;padding:11px;margin-top:7px;">
                <option value="Recepcionista">Recepcionista</option>
                <option value="Administrador">Administrador</option>
                <option value="Personal de acceso">Personal de acceso</option>
            </select></label>
            <div style="display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn-primary">Guardar usuario</button>
                <a href="{{ route('usuarios.index') }}">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
