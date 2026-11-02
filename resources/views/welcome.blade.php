@extends('layouts.guest')

@section('title', 'Compraventa de vehículos')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark mb-3">Compra · Venta · Consignación</span>
                    <h1 class="display-4 fw-bold">Tu próximo vehículo está en {{ config('app.name') }}</h1>
                    <p class="lead text-white-75 my-4">
                        Gestionamos el inventario de la compraventa de punta a punta: marcas, vehículos,
                        fotos, estados de venta e informes, todo en un solo lugar.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-accent btn-lg"><i class="bi bi-speedometer2 me-1"></i> Ir al panel</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-accent btn-lg"><i class="bi bi-person-plus me-1"></i> Crear cuenta</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Iniciar sesión</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <div class="display-6 fw-bold">{{ $stats['disponibles'] }}</div>
                                <small class="text-white-50">Vehículos disponibles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <div class="display-6 fw-bold">{{ $stats['marcas'] }}</div>
                                <small class="text-white-50">Marcas</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                <div class="display-6 fw-bold">{{ $stats['vendidos'] }}</div>
                                <small class="text-white-50">Vehículos vendidos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section id="servicios" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">¿Qué ofrecemos?</h2>
            <div class="row g-4 text-center">
                @foreach ([
                    ['bi-search', 'Inventario al día', 'Consulta los vehículos disponibles con fotos, precio y kilometraje.'],
                    ['bi-shield-check', 'Vehículos revisados', 'Cada vehículo se registra con placa, año y estado de venta.'],
                    ['bi-file-earmark-pdf', 'Informes en PDF', 'Descarga el inventario completo listo para imprimir o compartir.'],
                    ['bi-plug', 'API abierta', 'Integra nuestro inventario en otras aplicaciones mediante una API REST con tokens.'],
                ] as [$icono, $titulo, $texto])
                    <div class="col-sm-6 col-lg-3">
                        <div class="feature-icon mb-3"><i class="bi {{ $icono }}"></i></div>
                        <h5 class="fw-semibold">{{ $titulo }}</h5>
                        <p class="text-muted small mb-0">{{ $texto }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Últimos vehículos --}}
    <section id="inventario" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Recién llegados</h2>
            <div class="row g-4">
                @forelse ($destacados as $vehiculo)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-vehiculo h-100 shadow-sm border-0">
                            <img src="{{ $vehiculo->foto_url ?? 'https://placehold.co/600x400?text='.urlencode($vehiculo->marca->nombre) }}" class="card-img-top" alt="{{ $vehiculo->modelo }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title mb-1">{{ $vehiculo->marca->nombre }} {{ $vehiculo->modelo }}</h5>
                                    <span class="badge text-bg-light">{{ $vehiculo->anio }}</span>
                                </div>
                                <p class="text-muted small mb-2">
                                    {{ ucfirst($vehiculo->tipo) }} · {{ number_format($vehiculo->kilometraje, 0, ',', '.') }} km
                                </p>
                                <p class="fs-5 fw-bold text-accent mb-0">$ {{ number_format($vehiculo->precio, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">Pronto tendremos vehículos disponibles.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
