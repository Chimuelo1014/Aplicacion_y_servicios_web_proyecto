@extends('layouts.guest')

@section('title', 'Nueva contraseña')

@section('content')
    <div class="container py-5">
        <div class="card auth-card mx-auto shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="feature-icon mb-2"><i class="bi bi-shield-lock"></i></div>
                    <h1 class="h4 fw-bold">Restablecer contraseña</h1>
                </div>

                @include('partials.alerts')

                <form method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                               class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <input type="password" id="password" name="password" required autofocus
                               class="form-control @error('password') is-invalid @enderror">
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary bg-brand border-0 w-100 py-2">Guardar contraseña</button>
                </form>
            </div>
        </div>
    </div>
@endsection
