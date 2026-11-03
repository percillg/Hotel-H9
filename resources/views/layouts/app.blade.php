<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'HotelH9')</title>

    
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f6f8fb;
            color: #172033;
        }

        

        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;

            background: #10264f;
            color: white;

            display: flex;
            flex-direction: column;

            padding: 18px 12px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;

            margin-bottom: 25px;
            padding: 0 8px;
        }

        .logo-icon {
            width: 34px;
            height: 34px;

            background: #2563eb;
            border-radius: 7px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
        }

        .logo-text h2 {
            margin: 0;
            font-size: 17px;
        }

        .logo-text span {
            color: #9eacc5;
            font-size: 11px;
        }

        

        .menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 11px 12px;

            color: #aab7ce;
            text-decoration: none;

            border-radius: 7px;

            font-size: 14px;

            transition: 0.2s;
        }

        .menu-item i {
            width: 20px;
            font-size: 17px;
        }

        .menu-item:hover {
            background: #1d3156;
            color: white;
        }

        .menu-item.active {
            background: #1e3154;
            color: white;
            font-weight: 600;
        }

        .logout {
            margin-top: auto;
        }

        

        .main {
            margin-left: 220px;
            min-height: 100vh;
        }

        

        .header {
            height: 60px;

            background: white;

            border-bottom: 1px solid #dde4ee;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 24px;
        }

        .header h1 {
            font-size: 21px;
            margin: 0;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 12px;

            color: #66758d;
            font-size: 14px;
        }

        .avatar {
            width: 35px;
            height: 35px;

            background: #2563eb;
            color: white;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
        }

        

        .content {
            padding: 25px;
        }

        

        .card {
            background: white;

            border: 1px solid #dbe3ee;
            border-radius: 10px;

            padding: 20px;

            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
        }

        .btn-primary {
            background: #2563eb;
            color: white;

            border: none;
            border-radius: 7px;

            padding: 10px 18px;

            cursor: pointer;

            font-weight: 600;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        input,
        select,
        textarea {
            font-family: inherit;
        }

        

        @media (max-width: 900px) {

            .sidebar {
                width: 180px;
            }

            .main {
                margin-left: 180px;
            }

            .logo-text h2 {
                font-size: 15px;
            }

            .menu-item {
                font-size: 13px;
            }
        }


        .mobile-menu-button, .sidebar-backdrop { display: none; }

        .mobile-menu-button {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            color: #10264f;
            width: 44px;
            height: 44px;
            padding: 0;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            border: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 199;
            cursor: pointer;
        }

        @media (max-width: 780px) {
            .sidebar {
                width: min(290px, 85vw);
                height: 100dvh;
                z-index: 200;
                transform: translateX(-105%);
                transition: transform 0.22s ease;
                overflow-y: auto;
            }

            .main { margin-left: 0; width: 100%; min-width: 0; }
            .header { height: auto; min-height: 65px; padding: 10px 15px; justify-content: flex-start; gap: 12px; }
            .header h1 { font-size: 19px; min-width: 0; flex: 1; }
            .user-area { margin-left: auto; }
            .user-area span { display: none; }
            .mobile-menu-button { display: inline-flex; }
            .content { padding: 15px; min-width: 0; }
            .content > * { max-width: 100%; min-width: 0; }
            .card, .tabla-container, .form-container { max-width: 100%; }
            .content input, .content select, .content textarea { max-width: 100%; }
            .content .tabla-responsive { display: block; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .content .tabla-responsive table { min-width: 650px; }
            .content .form-grid { grid-template-columns: 1fr; }
            .content .form-group.full { grid-column: auto; }
            .content .form-actions { flex-wrap: wrap; }
            .content .huespedes-toolbar, .content .habitaciones-toolbar,
            .content .estadias-toolbar, .content .reservas-toolbar { align-items: stretch; }
            .content .busqueda-form, .content .filtros { flex-wrap: wrap; min-width: 0; }
            .content .input-busqueda { flex: 1 1 210px; min-width: 0; }
            .content .input-busqueda input { width: 100%; max-width: 100%; }
            .content .btn-buscar, .content .btn-nuevo, .content .btn-nueva,
            .content .btn-limpiar, .content .btn-primary { min-height: 44px; }
            body.sidebar-open { overflow: hidden; }
            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open .sidebar-backdrop { display: block; }
        }

        @media (max-width: 450px) {
            .header h1 { font-size: 17px; }
            .header .avatar { width: 32px; height: 32px; }
            .content { padding: 12px; }
            .content .card, .content .tabla-container, .content .form-container { padding: 15px; }
            .content .form-actions > * { flex: 1 1 130px; text-align: center; }
        }
    </style>

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/hotelh9-urban-slate.css') }}">

    <style id="hotelh9-urban-slate-inline">
:root {
    --h9-cloud: #E9E6E7;
    --h9-charcoal: #5E5653;
    --h9-stone: #7B7F8A;
    --h9-taupe: #AB978C;
    --h9-slate: #6B7C98;
    --h9-slate-dark: #526581;
    --h9-border: #cbc4c1;
    --h9-panel: #fffdfd;
    --h9-soft: #f5f2f1;
    --h9-text: #463f3b;
}

body,
body:not(.login-theme) {
    background-color: var(--h9-cloud) !important;
    color: var(--h9-text) !important;
    font-family: "Segoe UI", Arial, Helvetica, sans-serif;
}

.sidebar {
    background: var(--h9-charcoal) !important;
    color: #fff !important;
}

.logo-icon,
.avatar {
    background: var(--h9-slate-dark) !important;
    color: #fff !important;
}

.logo-text h2,
.logo-text span {
    color: #fff !important;
}

.menu-item {
    color: #f1ebea !important;
}

.menu-item:hover,
.menu-item:focus-visible {
    background: #756c68 !important;
    color: #fff !important;
}

.menu-item.active {
    background: var(--h9-slate-dark) !important;
    color: #fff !important;
}

.header {
    background: var(--h9-soft) !important;
    border-bottom: 1px solid var(--h9-border) !important;
    color: var(--h9-text) !important;
}

.header h1 {
    text-align: center !important;
    flex: 1;
    color: var(--h9-text) !important;
}

.user-area,
.logo-text span,
.content .form-description,
.content .info,
.content .dato-secundario,
.content .rut,
.content .tipo,
.content .panel-description,
.content .stat-detail,
.content .stat-label,
.content .resumen-label,
.content .info-label,
.content .tabla-footer {
    color: var(--h9-charcoal) !important;
}

.card,
.stat-card,
.panel,
.tabla-container,
.form-container,
.resumen-card,
.config-card,
.selector-card,
.info-card,
.content .card,
.content .panel {
    background: var(--h9-panel) !important;
    border-color: var(--h9-border) !important;
    box-shadow: 0 4px 16px rgba(94, 86, 83, 0.045) !important;
}

.content h1,
.content h2,
.content h3,
.content .panel-title,
.content .form-title,
.content .stat-value,
.content .resumen-numero,
.content .resumen-valor,
.content .info-value,
.content .nombre-huesped {
    color: var(--h9-text) !important;
}

.content .stat-icon,
.content .estado-icon:not(.estado-disponible):not(.estado-ocupada):not(.estado-limpieza) {
    background: var(--h9-cloud) !important;
    color: var(--h9-slate-dark) !important;
}

.content table {
    border-color: var(--h9-border) !important;
}

.content table thead,
.content table th,
.content .tabla-dashboard th,
.content .tabla-estadias thead {
    background: var(--h9-soft) !important;
    color: var(--h9-charcoal) !important;
}

.content table th,
.content table td {
    text-align: center !important;
    vertical-align: middle !important;
    border-color: var(--h9-border) !important;
}

.content table tbody tr:hover {
    background: #f6f3f2 !important;
}

.content .stat-header,
.content .stat-card,
.content .resumen-card,
.content .stat-label,
.content .stat-value,
.content .resumen-label,
.content .resumen-numero,
.content .resumen-valor,
.content .panel-header,
.content .panel-title,
.content .panel-description,
.content .form-title,
.content .form-description,
.content .sin-resultados,
.content .empty,
.content .tabla-footer {
    text-align: center !important;
}

.content .stat-header {
    justify-content: center !important;
    gap: 10px;
}

.content .panel-header {
    justify-content: center !important;
    flex-wrap: wrap;
    gap: 12px;
}

.content input:not([type="checkbox"]):not([type="radio"]),
.content select,
.content textarea {
    border-color: var(--h9-border) !important;
    background: #fff !important;
    color: var(--h9-text) !important;
}

.content input:focus-visible,
.content select:focus-visible,
.content textarea:focus-visible,
.login-theme input:focus-visible {
    border-color: var(--h9-slate) !important;
    outline: 2px solid rgba(107, 124, 152, 0.35) !important;
    outline-offset: 1px;
}

.content .btn-primary,
.content .btn-buscar,
.content .btn-nuevo,
.content .btn-nueva,
.content .btn-guardar,
.content .btn-filtrar,
.content .btn-checkin {
    background: var(--h9-slate-dark) !important;
    color: white !important;
    border-color: var(--h9-slate-dark) !important;
    text-align: center !important;
}

.content .btn-primary:hover,
.content .btn-buscar:hover,
.content .btn-nuevo:hover,
.content .btn-nueva:hover,
.content .btn-guardar:hover,
.content .btn-filtrar:hover,
.content .btn-checkin:hover {
    background: var(--h9-charcoal) !important;
    border-color: var(--h9-charcoal) !important;
}

.content .btn-checkout {
    background: var(--h9-charcoal) !important;
    color: white !important;
    border-color: var(--h9-charcoal) !important;
}

.content .btn-checkout:hover {
    background: #49413e !important;
}

.content .btn-cancelar,
.content .btn-limpiar,
.content .btn-accion,
.content .btn-detalle {
    background: #fff !important;
    color: var(--h9-charcoal) !important;
    border-color: var(--h9-border) !important;
    text-align: center !important;
}

.content .btn-accion:hover,
.content .btn-detalle:hover,
.content .btn-cancelar:hover,
.content .btn-limpiar:hover {
    background: var(--h9-soft) !important;
}

.content a:not([class]),
.content .panel-link {
    color: var(--h9-slate-dark) !important;
}

.content .badge-disponible,
.content .badge-ocupada,
.content .badge-limpieza,
.content .badge-pendiente,
.content .badge-activa,
.content .estado-disponible,
.content .estado-ocupada,
.content .estado-limpieza,
.content .badge-pagado,
.content .badge-anulado,
.content .alert-success,
.content .alert-error {
    text-align: center !important;
}

.content .form-actions,
.content .acciones {
    justify-content: center !important;
}

.content .tabla-estadias td:last-child,
.content .tabla-estadias th:last-child {
    min-width: 245px;
}

.content .tabla-estadias td:last-child form {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    margin: 0 auto;
}

.content .tabla-estadias .etiqueta-fecha {
    display: block;
    margin: 0;
    text-align: center !important;
    color: var(--h9-charcoal) !important;
}

.content .tabla-estadias .fecha-operacion {
    width: 224px !important;
    max-width: 100%;
    height: 42px !important;
    margin: 0 auto 2px !important;
    text-align: center !important;
    border-color: var(--h9-border) !important;
    color: var(--h9-text) !important;
}

.content .tabla-estadias .btn-checkin,
.content .tabla-estadias .btn-checkout {
    box-sizing: border-box;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 224px !important;
    max-width: 100%;
    min-height: 44px !important;
    padding: 10px 12px !important;
    margin: 0 auto;
    border-radius: 7px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    white-space: nowrap;
    line-height: 1.2;
}

.mobile-menu-button {
    background: var(--h9-panel) !important;
    border-color: var(--h9-border) !important;
    color: var(--h9-charcoal) !important;
}

.login-theme {
    background: var(--h9-cloud) !important;
    color: var(--h9-text) !important;
}

.login-theme .panel {
    background: var(--h9-panel) !important;
    border: 1px solid var(--h9-border);
    box-shadow: 0 14px 35px rgba(94, 86, 83, .13) !important;
    text-align: center;
}

.login-theme h1 {
    color: var(--h9-charcoal) !important;
    text-align: center;
}

.login-theme p {
    color: var(--h9-stone) !important;
    text-align: center;
}

.login-theme label {
    color: var(--h9-charcoal) !important;
    text-align: center;
}

.login-theme input {
    border-color: var(--h9-border) !important;
    color: var(--h9-text) !important;
    background: #fff !important;
}

.login-theme button[type="submit"] {
    background: var(--h9-slate-dark) !important;
    color: #fff !important;
    font-family: inherit !important;
    text-align: center !important;
    min-height: 46px;
}

.login-theme button[type="submit"]:hover {
    background: var(--h9-charcoal) !important;
}

.login-theme .error {
    text-align: center;
}

@media (max-width: 780px) {
    .header h1 {
        text-align: center !important;
    }
    .content .panel-header {
        flex-direction: column;
    }
    .content .tabla-responsive {
        max-width: 100%;
        overflow-x: auto;
    }
    .content table {
        min-width: max-content;
    }
    .content .form-actions {
        flex-wrap: wrap;
    }
}

@media (max-width: 450px) {
    .login-theme .panel {
        padding: 25px 20px !important;
    }
    .content .tabla-estadias .btn-checkin,
    .content .tabla-estadias .btn-checkout,
    .content .tabla-estadias .fecha-operacion {
        width: 224px !important;
    }
}

:root {
    --h9-cloud: #E9E6E7;
    --h9-charcoal: #5E5653;
    --h9-stone: #7B7F8A;
    --h9-taupe: #AB978C;
    --h9-slate: #6B7C98;
    --h9-slate-dark: #4F4845;
    --h9-border: #B9AFAA;
    --h9-panel: #F8F6F5;
    --h9-soft: #E9E6E7;
    --h9-text: #302B29;
}

html, body, body:not(.login-theme), body.login-theme {
    background: #5E5653 !important;
}

.main, .main > .content, .content {
    background-color: #5E5653 !important;
}

.sidebar {
    background-color: #4F4845 !important;
}

.header {
    background-color: #E9E6E7 !important;
    border-color: #AB978C !important;
}

.card, .panel, .stat-card, .tabla-container, .resumen-card, .form-container, .config-card, .info-card {
    background-color: #F8F6F5 !important;
    color: #302B29 !important;
    border-color: #B9AFAA !important;
}

.login-theme {
    min-height: 100vh;
    background-color: #5E5653 !important;
}

.login-theme .panel {
    background-color: #E9E6E7 !important;
    border: 1px solid #AB978C !important;
    box-shadow: 0 18px 44px rgba(30,25,23,.2) !important;
}

.login-theme .panel h1 {
    color: #4F4845 !important;
}

.login-theme .panel p {
    color: #5E5653 !important;
}

.login-theme .panel button[type="submit"] {
    background-color: #4F4845 !important;
    color: #FFFFFF !important;
}

.login-theme .panel button[type="submit"]:hover {
    background-color: #5E5653 !important;
}

.content .btn-primary, .content .btn-guardar, .content .btn-checkin, .content .btn-buscar {
    background-color: #4F4845 !important;
    border-color: #4F4845 !important;
    color: #FFFFFF !important;
}

.content .tabla-estadias .btn-checkin, .content .tabla-estadias .btn-checkout {
    width: 224px !important;
    max-width: 100%;
    min-height: 44px !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    font-size: 13px !important;
    box-sizing: border-box;
}

.content table th, .content table td {
    text-align: center !important;
    vertical-align: middle !important;
}

.content .form-actions, .content .acciones {
    justify-content: center !important;
}
</style>
    <style id="hotelh9-tarjetas-globales">
:root {
    --h9-panel: #C4B5AA;
    --h9-soft: #D8CBC3;
    --h9-border: #A99183;
    --h9-text: #332C29;
}

.content :is(.stat-card, .panel, .card, .tabla-container, .form-container, .resumen-card, .config-card, .selector-card, .info-card, .filtros-card, .tabla-card, .dashboard-card, .summary-card, .content-card, .form-card, .widget, .content-box, .module-card),
.content :is(.stat-card, .panel, .card, .tabla-container, .form-container, .resumen-card, .config-card, .selector-card, .info-card, .filtros-card, .tabla-card, .dashboard-card, .summary-card, .content-card, .form-card, .widget, .content-box, .module-card) > :is(.card-body, .panel-body, .widget-body),
.login-theme .panel,
.login-theme .login-card {
    background: #C4B5AA !important;
    background-color: #C4B5AA !important;
    border-color: #A99183 !important;
    color: #332C29 !important;
    box-shadow: 0 4px 12px rgba(37, 29, 25, .09) !important;
}

.content :is(.card-header, .panel-header, .widget-header, .box-header),
.content .resumen-header {
    background: transparent !important;
    color: #332C29 !important;
}

.content :is(.stat-icon, .resumen-icon),
.content .panel .estado-icon:not(.estado-disponible):not(.estado-ocupada):not(.estado-limpieza) {
    background: #D8CBC3 !important;
    color: #5E5653 !important;
}

.content :is(.stat-label, .stat-detail, .stat-value, .resumen-label, .resumen-numero, .resumen-valor, .panel-title, .panel-description, .info-label, .info-value, .form-title),
.login-theme .panel :is(h1, p, label) {
    color: #332C29 !important;
}

.content :is(.tabla-dashboard, .tabla-reservas, .tabla-habitaciones, .tabla-estadias, .tabla-responsive),
.content :is(.tabla-dashboard, .tabla-reservas, .tabla-habitaciones, .tabla-estadias) tbody,
.content :is(.tabla-dashboard, .tabla-reservas, .tabla-habitaciones, .tabla-estadias) tbody tr {
    background: transparent !important;
    background-color: transparent !important;
}

.content table thead th {
    background: #B4A196 !important;
    color: #302824 !important;
    border-color: #A99183 !important;
}

.content table tbody tr:hover {
    background: #BDA99E !important;
}

.content input:not([type="checkbox"]):not([type="radio"]),
.content select,
.content textarea,
.login-theme input {
    background: #F5F0EC !important;
    color: #332C29 !important;
}
</style>
</head>

<body>

    <button type="button" class="sidebar-backdrop" id="sidebar-backdrop" aria-label="Cerrar menú" tabindex="-1"></button>

    

    <aside class="sidebar" id="hotel-sidebar" aria-label="Navegación principal">

        <div class="logo">

            <div class="logo-icon">
                <i class="bi bi-building"></i>
            </div>

            <div class="logo-text">
                <h2>HotelH9</h2>
                <span>Sistema de administración</span>
            </div>

        </div>


        <nav class="menu">

            @if (session('usuario.rol') !== 'Mantenimiento')
<a href="{{ route('dashboard') }}"
               class="menu-item @yield('menu-dashboard')">

                <i class="bi bi-speedometer2"></i>
                Dashboard

            </a>
@endif


            <a href="{{ route('habitaciones.index') }}"
               class="menu-item @yield('menu-habitaciones')">

                <i class="bi bi-building"></i>
                Habitaciones

            </a>


            @if (session('usuario.rol') !== 'Mantenimiento')
<a href="{{ route('reservas.index') }}"
               class="menu-item @yield('menu-reservas')">

                <i class="bi bi-calendar-check"></i>
                Reservas

            </a>
@endif


            @if (session('usuario.rol') !== 'Mantenimiento')
<a href="{{ route('huespedes.index') }}"
               class="menu-item @yield('menu-huespedes')">

                <i class="bi bi-people"></i>
                Huéspedes

            </a>
@endif


            @if (session('usuario.rol') !== 'Mantenimiento')
<a href="{{ route('estadias.index') }}"
               class="menu-item @yield('menu-checkin')">

                <i class="bi bi-arrow-left-right"></i>
                Check-in/Check-out

            </a>
@endif


            @if (session('usuario.rol') !== 'Mantenimiento')
<a href="{{ route('pagos.index') }}"
               class="menu-item @yield('menu-pagos')">

                <i class="bi bi-credit-card"></i>
                Pagos y Servicios

            </a>
@endif


            @if (session('usuario.rol') === 'Administrador')
<a href="{{ route('reportes.index') }}"
               class="menu-item @yield('menu-reportes')">

                <i class="bi bi-bar-chart"></i>
                Reportes

            </a>
@endif


            @if (session('usuario.rol') === 'Administrador')
<a href="{{ route('usuarios.index') }}"
               class="menu-item @yield('menu-usuarios')">

                <i class="bi bi-person-gear"></i>
                Usuarios

            </a>
@endif


            @if (session('usuario.rol') === 'Administrador')
<a href="{{ route('configuracion.index') }}"
               class="menu-item">

                <i class="bi bi-gear"></i>
                Configuración

            </a>
@endif

        </nav>


        <form class="logout" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="menu-item" type="submit" style="width: 100%; background: transparent; border: 0; cursor: pointer; text-align: left;">
                <i class="bi bi-box-arrow-left"></i>
                Cerrar sesión
            </button>
        </form>

    </aside>


    

    <main class="main">

        <header class="header">

            <button type="button" class="mobile-menu-button" id="mobile-menu-button" aria-label="Abrir menú" aria-controls="hotel-sidebar" aria-expanded="false">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>

            <h1>
                @yield('page-title', 'Dashboard')
            </h1>

            <div class="user-area">

                <span>Bienvenido/a, {{ session('usuario.nombre', 'Personal') }}</span>

                <div class="avatar">
                    RE
                </div>

            </div>

        </header>


        <section class="content">

            @yield('content')

        </section>

    </main>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trigger = document.getElementById('mobile-menu-button');
            const backdrop = document.getElementById('sidebar-backdrop');
            const sidebar = document.getElementById('hotel-sidebar');
            const toggle = function (open) {
                document.body.classList.toggle('sidebar-open', open);
                trigger.setAttribute('aria-expanded', String(open));
                trigger.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
                trigger.querySelector('i').className = open ? 'bi bi-x-lg' : 'bi bi-list';
            };

            trigger.addEventListener('click', function () {
                toggle(!document.body.classList.contains('sidebar-open'));
            });
            backdrop.addEventListener('click', function () { toggle(false); });
            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () { toggle(false); });
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') toggle(false);
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth > 780) toggle(false);
            });
        });
    </script>
    @stack('scripts')

</body>
</html>