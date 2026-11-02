@extends('layouts.app')

@section('title', $vehiculo->placa)
@section('header')
    {{ $vehiculo->marca->nombre }} {{ $vehiculo->modelo }} <small class="text-muted fs-6">{{ $vehiculo->anio }}</small>
@endsection
@section('actions')
    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-primary bg-brand border-0"><i class="bi bi-pencil me-1"></i> Editar</a>
    <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Volver</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <img src="{{ $vehiculo->foto_url ?? 'https://placehold.co/900x600?text=Sin+foto' }}" class="img-fluid rounded shadow-sm w-100" alt="">
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="display-6 fw-bold text-accent">$ {{ number_format($vehiculo->precio, 0, ',', '.') }}</span>
                        @include('vehiculos._estado', ['estado' => $vehiculo->estado])
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-5">Placa</dt><dd class="col-7">{{ $vehiculo->placa }}</dd>
                        <dt class="col-5">Tipo</dt><dd class="col-7">{{ ucfirst($vehiculo->tipo) }}</dd>
                        <dt class="col-5">Color</dt><dd class="col-7">{{ $vehiculo->color ?? '—' }}</dd>
                        <dt class="col-5">Kilometraje</dt><dd class="col-7">{{ number_format($vehiculo->kilometraje, 0, ',', '.') }} km</dd>
                        <dt class="col-5">Registrado por</dt><dd class="col-7">{{ $vehiculo->registradoPor?->name ?? '—' }}</dd>
                        <dt class="col-5">Fecha registro</dt><dd class="col-7">{{ $vehiculo->created_at->format('d/m/Y') }}</dd>
                    </dl>
                    @if ($vehiculo->descripcion)
                        <hr>
                        <p class="mb-0">{{ $vehiculo->descripcion }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
