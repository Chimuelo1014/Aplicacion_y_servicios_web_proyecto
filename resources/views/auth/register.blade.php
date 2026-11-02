@extends('layouts.guest')

@section('title', 'Registro')

@section('content')
    <div class="container py-5">
        <div class="card auth-card mx-auto shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon mb-2"><i class="bi bi-person-plus"></i></div>
                    <h1 class="h4 fw-bold">Crear cuenta</h1>
                    <p class="text-muted small">Regístrate para acceder al panel</p>
                </div>

                @include('partials.alerts')

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               class="form-control @error('name') is-invalid @enderror">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" required
                               class="form-control @error('password') is-invalid @enderror">
                        <div class="form-text">Mínimo 8 caracteres, con letras y números.</div>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary bg-brand border-0 w-100 py-2">Registrarme</button>
                </form>

                <p class="text-center small mt-4 mb-0">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
@endsection
