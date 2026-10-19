@extends('layouts.app')
@section('title', 'Usuarios - Gestión Hotel')
@section('page-title', 'Usuarios del hotel')
@section('menu-usuarios', 'active')
@section('content')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:20px;">
            <div>
                <h2 style="margin:0 0 6px;">Personal registrado</h2>
                <p style="margin:0; color:#64748b;">Cuentas y roles almacenados en MongoDB</p>
            </div>
            @if (session('usuario.rol') === 'Administrador')
                <a href="{{ route('usuarios.create') }}" class="btn-primary">Nuevo usuario</a>
            @endif
        </div>
        @if (session('success'))
            <p style="color:#15803d;">{{ session('success') }}</p>
        @endif
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr><th style="padding:13px;">Nombre</th><th>Usuario</th><th>Rol</th></tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr style="border-top:1px solid #e2e8f0;">
                            <td style="padding:13px;">{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                            <td>{{ $usuario->nombre_usuario }}</td>
                            <td>{{ $usuario->rol }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="padding:20px;">No hay usuarios registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
