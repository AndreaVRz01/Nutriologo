<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriSystem - Gestión Clínica</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Isometra&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-container">
        <!-- Sidebar lateral según solicitud -->
        <aside class="sidebar">
            <div>
                <div class="sidebar-title">NutriConsultorio</div>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('citas.index') }}" class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">
                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                            <span>Agenda de Citas</span>
                        </a>
                    </li>
                    @if(auth()->user()->isNutriologo())
                    <li>
                        <a href="{{ route('formularios.index') }}" class="nav-link {{ request()->routeIs('formularios.*') ? 'active' : '' }}">
                            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                            <span>Formulario Clínico</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <div>
                <button id="theme-toggle" class="btn btn-secondary" style="width: 100%; margin-bottom: 1rem;">
                    <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-5.4-5.4c0-1.81.89-3.42 2.26-4.4C12.92 3.04 12.46 3 12 3z"/></svg>
                    <span>Modo Claro/Oscuro</span>
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header-top">
                <h2>Bienvenido, {{ auth()->user()->name }}</h2>
                <span class="btn btn-accent">{{ ucfirst(auth()->user()->role) }}</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>