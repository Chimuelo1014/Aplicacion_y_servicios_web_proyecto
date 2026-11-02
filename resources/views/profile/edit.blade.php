@extends('layouts.app')

@section('title', 'Mi perfil')
@section('header', 'Editar perfil')

@section('content')
    <div class="row g-4">
        {{-- Datos personales --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Información personal</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <img id="previewAvatar" src="{{ $user->avatar_url }}" class="rounded-circle object-fit-cover" width="96" height="96" alt="Avatar">
                            <div>
                                <label for="avatar" class="form-label mb-1">Foto de perfil</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control form-control-sm">
                                <div class="form-text">JPG, PNG o WEBP. Máx 2 MB.</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Nombre</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
                        </div>
                        <button class="btn btn-primary bg-brand border-0"><i class="bi bi-save me-1"></i> Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 d-flex flex-column gap-4">
            {{-- Contraseña --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Cambiar contraseña</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="current_password">Contraseña actual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Nueva contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirmar</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-outline-secondary">Actualizar contraseña</button>
                    </form>
                </div>
            </div>

            {{-- Tokens de API --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Tokens de API</div>
                <div class="card-body">
                    @if (session('token'))
                        <div class="alert alert-warning small">
                            Copia tu token ahora, no se volverá a mostrar:
                            <code class="d-block mt-1 user-select-all text-break">{{ session('token') }}</code>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('profile.tokens.store') }}" class="input-group mb-3">
                        @csrf
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre (ej. postman)" required>
                        <button class="btn btn-accent">Generar</button>
                    </form>
                    <ul class="list-group list-group-flush small">
                        @forelse ($tokens as $token)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="bi bi-key me-1"></i>{{ $token->name }}
                                    <span class="text-muted">· {{ $token->last_used_at ? 'usado '.$token->last_used_at->diffForHumans() : 'sin uso' }}</span></span>
                                <form method="POST" action="{{ route('profile.tokens.destroy', $token->id) }}" class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0">Revocar</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">No tienes tokens activos.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatar').addEventListener('change', e => {
            const [archivo] = e.target.files;
            if (archivo) document.getElementById('previewAvatar').src = URL.createObjectURL(archivo);
        });
    </script>
@endpush
