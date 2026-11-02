@extends('layouts.app')

@php($editando = $marca->exists)

@section('title', $editando ? 'Editar marca' : 'Nueva marca')
@section('header', $editando ? 'Editar marca' : 'Nueva marca')

@section('content')
    <div class="card border-0 shadow-sm" style="max-width: 640px">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ $editando ? route('marcas.update', $marca) : route('marcas.store') }}">
                @csrf
                @if ($editando) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label" for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $marca->nombre) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="pais_origen">País de origen</label>
                    <input type="text" id="pais_origen" name="pais_origen" class="form-control"
                           value="{{ old('pais_origen', $marca->pais_origen) }}">
                </div>
                <div class="mb-4">
                    <label class="form-label" for="logo">Logo</label>
                    @if ($marca->logo)
                        <div class="mb-2"><img src="{{ asset('storage/'.$marca->logo) }}" height="60" alt=""></div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary bg-brand border-0"><i class="bi bi-save me-1"></i> Guardar</button>
                    <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
