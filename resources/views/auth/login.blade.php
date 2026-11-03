<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — Gestión Hotel H9</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f1f5f9; color: #172033; min-height: 100vh; display: grid; place-items: center; }
        .panel { width: min(410px, calc(100% - 32px)); background: white; border-radius: 14px; padding: 34px; box-shadow: 0 12px 32px #10264f19; }
        h1 { margin: 0 0 8px; color: #10264f; font-size: 26px; }
        p { color: #64748b; margin-bottom: 26px; }
        label { display: block; margin: 16px 0 7px; font-weight: bold; font-size: 14px; }
        input { box-sizing: border-box; display: block; width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; font-size: 15px; }
        button { width: 100%; margin-top: 24px; background: #2563eb; color: white; border: 0; padding: 13px; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .error { margin-top: 12px; color: #b91c1c; font-size: 14px; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/hotelh9-urban-slate.css') }}">
</head>
<body class="login-theme">
    <main class="panel">
       <h1>HotelH9</h1>
	<p>Ingreso de personal</p>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <label for="nombre_usuario">Usuario</label>
            <input id="nombre_usuario" name="nombre_usuario" value="{{ old('nombre_usuario') }}" autocomplete="username" required>
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @if ($errors->any())
                <div class="error" role="alert">{{ $errors->first() }}</div>
            @endif
            <button type="submit">Ingresar</button>
        </form>
    </main>
</body>
</html>
