<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') · {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="app-body">
    @php($usuario = auth()->user())

    {{-- Barra superior (móvil) --}}
    <nav class="navbar navbar-dark bg-brand d-lg-none px-3">
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <i class="bi bi-list"></i>
        </button>
        <span class="navbar-brand mb-0 fw-bold"><i class="bi bi-car-front-fill"></i> {{ config('app.name') }}</span>
    </nav>

    <div class="d-flex">
        {{-- Menú lateral --}}
        <aside class="sidebar offcanvas-lg offcanvas-start bg-brand text-white" tabindex="-1" id="sidebar">
            <div class="offcanvas-header d-lg-none">
                <span class="fw-bold">{{ config('app.name') }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-3 h-100">
                <a href="{{ route('home') }}" class="d-none d-lg-flex align-items-center text-white text-decoration-none fs-5 fw-bold mb-3">
                    <i class="bi bi-car-front-fill me-2"></i> {{ config('app.name') }}
                </a>

                {{-- Usuario activo --}}
                <div class="user-card d-flex align-items-center gap-2 p-2 rounded mb-3">
                    <img src="{{ $usuario->avatar_url }}" alt="Avatar" class="rounded-circle object-fit-cover" width="44" height="44">
                    <div class="lh-sm overflow-hidden">
                        <div class="fw-semibold text-truncate">{{ $usuario->name }}</div>
                        <small class="text-white-50 text-truncate d-block">{{ $usuario->email }}</small>
                    </div>
                </div>

                <ul class="nav nav-pills flex-column gap-1 mb-auto">
                    <li><a href="{{ route('dashboard') }}" class="nav-link text-white @if(request()->routeIs('dashboard')) active @endif"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <li><a href="{{ route('vehiculos.index') }}" class="nav-link text-white @if(request()->routeIs('vehiculos.*')) active @endif"><i class="bi bi-car-front me-2"></i>Listar vehículos</a></li>
                    <li><a href="{{ route('marcas.index') }}" class="nav-link text-white @if(request()->routeIs('marcas.*')) active @endif"><i class="bi bi-tags me-2"></i>Listar marcas</a></li>
                    <li><a href="{{ route('reportes.vehiculos') }}" class="nav-link text-white"><i class="bi bi-file-earmark-pdf me-2"></i>Informe PDF</a></li>
                    <li><a href="{{ route('api.cliente') }}" class="nav-link text-white @if(request()->routeIs('api.cliente')) active @endif"><i class="bi bi-plug me-2"></i>Cliente API</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="nav-link text-white @if(request()->routeIs('profile.*')) active @endif"><i class="bi bi-person-gear me-2"></i>Editar perfil</a></li>
                </ul>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100"><i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión</button>
                </form>
            </div>
        </aside>

        {{-- Contenido --}}
        <main class="content flex-grow-1 p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h1 class="h3 mb-0">@yield('header')</h1>
                <div>@yield('actions')</div>
            </div>

            @include('partials.alerts')

            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Configuración común de DataTables en español
        window.dtEspanol = { language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json' } };

        // Confirmación antes de eliminar
        document.addEventListener('submit', e => {
            if (e.target.matches('.form-eliminar') && !confirm('¿Seguro que deseas eliminar este registro?')) {
                e.preventDefault();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
