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

            <a href="{{ route('dashboard') }}"
               class="menu-item @yield('menu-dashboard')">

                <i class="bi bi-speedometer2"></i>
                Dashboard

            </a>


            <a href="{{ route('habitaciones.index') }}"
               class="menu-item @yield('menu-habitaciones')">

                <i class="bi bi-building"></i>
                Habitaciones

            </a>


            <a href="{{ route('reservas.index') }}"
               class="menu-item @yield('menu-reservas')">

                <i class="bi bi-calendar-check"></i>
                Reservas

            </a>


            <a href="{{ route('huespedes.index') }}"
               class="menu-item @yield('menu-huespedes')">

                <i class="bi bi-people"></i>
                Huéspedes

            </a>


            <a href="{{ route('estadias.index') }}"
               class="menu-item @yield('menu-checkin')">

                <i class="bi bi-arrow-left-right"></i>
                Check-in/Check-out

            </a>


            <a href="{{ route('pagos.index') }}"
               class="menu-item @yield('menu-pagos')">

                <i class="bi bi-credit-card"></i>
                Pagos y Servicios

            </a>


            <a href="{{ route('reportes.index') }}"
               class="menu-item @yield('menu-reportes')">

                <i class="bi bi-bar-chart"></i>
                Reportes

            </a>


            <a href="{{ route('usuarios.index') }}"
               class="menu-item @yield('menu-usuarios')">

                <i class="bi bi-person-gear"></i>
                Usuarios

            </a>


            <a href="{{ route('configuracion.index') }}"
               class="menu-item">

                <i class="bi bi-gear"></i>
                Configuración

            </a>

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