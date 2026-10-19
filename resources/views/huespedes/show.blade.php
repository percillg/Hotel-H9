@extends('layouts.app')

@section('title', 'Ficha del huésped - HotelH9')
@section('page-title', 'Detalle del huésped')
@section('menu-huespedes', 'active')

@push('styles')
<style>
    .ficha-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px; }
    .ficha-header h2 { margin: 0 0 6px; font-size: 22px; }
    .ficha-header p { margin: 0; color: #64748b; font-size: 14px; }
    .ficha-acciones { display: flex; flex-wrap: wrap; gap: 8px; }
    .ficha-acciones a { display: inline-flex; gap: 8px; align-items: center; justify-content: center; min-height: 44px; padding: 10px 15px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; }
    .ficha-volver { color: #475569; border: 1px solid #cbd5e1; background: white; }
    .ficha-editar { color: white; background: #2563eb; }
    .ficha-card { background: white; border: 1px solid #dbe3ee; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
    .ficha-card h3 { font-size: 17px; margin: 0 0 18px; }
    .ficha-datos { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .ficha-dato { min-width: 0; }
    .ficha-label { color: #64748b; font-size: 13px; margin-bottom: 6px; }
    .ficha-valor { font-weight: 600; overflow-wrap: anywhere; }
    .ficha-alerta { padding: 12px 16px; border-radius: 8px; background: #ecfdf5; color: #047857; margin-bottom: 18px; }
    .ficha-tabla { max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .ficha-tabla table { border-collapse: collapse; width: 100%; min-width: 580px; font-size: 14px; }
    .ficha-tabla th, .ficha-tabla td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; }
    .ficha-tabla th { color: #64748b; background: #f8fafc; }
    .ficha-vacio { color: #64748b; font-size: 14px; margin: 0; }
    @media (max-width: 700px) { .ficha-header { align-items: stretch; } .ficha-header h2 { font-size: 19px; } .ficha-acciones a { flex: 1; } .ficha-datos { grid-template-columns: 1fr; } .ficha-card { padding: 18px; } }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="ficha-alerta" role="status">{{ session('success') }}</div>
@endif
<div class="ficha-header">
    <div>
        <h2>{{ $huesped->nombre }} {{ $huesped->apellido }}</h2>
        <p>Ficha del huésped #{{ $huesped->_id }}</p>
    </div>
    <div class="ficha-acciones">
        <a class="ficha-volver" href="{{ route('huespedes.index') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i> Volver</a>
        <a class="ficha-editar" href="{{ route('huespedes.edit', $huesped->_id) }}"><i class="bi bi-pencil" aria-hidden="true"></i> Editar</a>
    </div>
</div>
<div class="ficha-card">
    <h3>Información personal y de contacto</h3>
    <div class="ficha-datos">
        <div class="ficha-dato"><div class="ficha-label">Nombre completo</div><div class="ficha-valor">{{ $huesped->nombre }} {{ $huesped->apellido }}</div></div>
        <div class="ficha-dato"><div class="ficha-label">RUT</div><div class="ficha-valor">{{ $huesped->rut }}</div></div>
        <div class="ficha-dato"><div class="ficha-label">Teléfono</div><div class="ficha-valor">{{ $huesped->telefono ?: 'No registrado' }}</div></div>
        <div class="ficha-dato"><div class="ficha-label">Email</div><div class="ficha-valor">{{ $huesped->email ?: 'No registrado' }}</div></div>
        <div class="ficha-dato"><div class="ficha-label">Dirección</div><div class="ficha-valor">{{ $huesped->direccion ?: 'No registrada' }}</div></div>
    </div>
</div>
<div class="ficha-card">
    <h3>Historial de reservas ({{ $reservas->count() }})</h3>
    @if($reservas->isEmpty())
        <p class="ficha-vacio">Este huésped todavía no tiene reservas registradas.</p>
    @else
        <div class="ficha-tabla">
            <table>
                <thead><tr><th>Reserva</th><th>Habitación(es)</th><th>Entrada</th><th>Salida</th><th>Estado</th></tr></thead>
                <tbody>
                @foreach($reservas as $reserva)
                    <tr>
                        <td>#{{ $reserva->_id }}</td>
                        <td>{{ collect($reserva->habitaciones)->pluck('numero')->implode(', ') }}</td>
                        <td>{{ substr((string) $reserva->fecha_entrada, 0, 10) }}</td>
                        <td>{{ substr((string) $reserva->fecha_salida, 0, 10) }}</td>
                        <td>{{ $reserva->estado }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
