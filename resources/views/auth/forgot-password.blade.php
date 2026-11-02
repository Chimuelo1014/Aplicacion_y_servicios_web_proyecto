@extends('layouts.guest')

@section('title', 'Recuperar contraseña')

@section('content')
    <div class="container py-5">
        <div class="card auth-card mx-auto shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon mb-2"><i class="bi bi-question-circle"></i></div>
                    <h1 class="h4 fw-bold">¿Olvidaste tu contraseña?</h1>
                    <p class="text-muted small">Escribe tu correo y te enviaremos un enlace para crear una nueva.</p>
                </div>

                @include('partials.alerts')

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <button type="submit" class="btn btn-primary bg-brand border-0 w-100 py-2">Enviar enlace</button>
                </form>

                <p class="text-center small mt-4 mb-0">
                    <a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Volver al inicio de sesión</a>
                </p>
            </div>
        </div>
    </div>
@endsection
