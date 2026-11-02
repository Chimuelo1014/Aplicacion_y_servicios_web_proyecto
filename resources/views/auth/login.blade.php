@extends('layouts.guest')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="container py-5">
        <div class="card auth-card mx-auto shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon mb-2"><i class="bi bi-person-lock"></i></div>
                    <h1 class="h4 fw-bold">Iniciar sesión</h1>
                    <p class="text-muted small">Ingresa para gestionar el inventario</p>
                </div>

                @include('partials.alerts')

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="form-control @error('email') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" id="password" name="password" required
                                   class="form-control @error('password') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Recordarme</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary bg-brand border-0 w-100 py-2">Entrar</button>
                </form>

                <p class="text-center small mt-4 mb-0">
                    ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
                </p>
            </div>
        </div>
    </div>
@endsection
