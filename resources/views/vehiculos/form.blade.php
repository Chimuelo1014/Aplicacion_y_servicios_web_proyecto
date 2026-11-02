@extends('layouts.app')

@php($editando = $vehiculo->exists)

@section('title', $editando ? 'Editar vehículo' : 'Nuevo vehículo')
@section('header', $editando ? 'Editar vehículo '.$vehiculo->placa : 'Nuevo vehículo')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $editando ? route('vehiculos.update', $vehiculo) : route('vehiculos.store') }}">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="marca_id">Marca *</label>
                            <select id="marca_id" name="marca_id" class="form-select @error('marca_id') is-invalid @enderror" required>
                                <option value="">Selecciona…</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}" @selected(old('marca_id', $vehiculo->marca_id) == $marca->id)>{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="modelo">Modelo *</label>
                            <div class="input-group">
                                <input type="text" id="modelo" name="modelo" list="sugerenciasModelos"
                                       class="form-control @error('modelo') is-invalid @enderror"
                                       value="{{ old('modelo', $vehiculo->modelo) }}" required>
                                <button type="button" id="btnSugerir" class="btn btn-outline-secondary" title="Sugerir modelos (API NHTSA)">
                                    <i class="bi bi-magic"></i>
                                </button>
                            </div>
                            <datalist id="sugerenciasModelos"></datalist>
                            <div id="ayudaModelos" class="form-text">Usa <i class="bi bi-magic"></i> para traer modelos desde un servicio web externo.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="anio">Año *</label>
                            <input type="number" id="anio" name="anio" class="form-control @error('anio') is-invalid @enderror"
                                   value="{{ old('anio', $vehiculo->anio) }}" min="1950" max="{{ date('Y') + 1 }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="placa">Placa *</label>
                            <input type="text" id="placa" name="placa" class="form-control text-uppercase @error('placa') is-invalid @enderror"
                                   value="{{ old('placa', $vehiculo->placa) }}" maxlength="10" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="color">Color</label>
                            <input type="text" id="color" name="color" class="form-control" value="{{ old('color', $vehiculo->color) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="tipo">Tipo *</label>
                            <select id="tipo" name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                @foreach (\App\Models\Vehiculo::TIPOS as $tipo)
                                    <option value="{{ $tipo }}" @selected(old('tipo', $vehiculo->tipo) === $tipo)>{{ ucfirst($tipo) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="kilometraje">Kilometraje *</label>
                            <input type="number" id="kilometraje" name="kilometraje" class="form-control @error('kilometraje') is-invalid @enderror"
                                   value="{{ old('kilometraje', $vehiculo->kilometraje ?? 0) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="precio">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" id="precio" name="precio" step="1000" class="form-control @error('precio') is-invalid @enderror"
                                       value="{{ old('precio', $vehiculo->precio) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="4" class="form-control">{{ old('descripcion', $vehiculo->descripcion) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <label class="form-label" for="estado">Estado *</label>
                        <select id="estado" name="estado" class="form-select mb-3">
                            @foreach (\App\Models\Vehiculo::ESTADOS as $estado)
                                <option value="{{ $estado }}" @selected(old('estado', $vehiculo->estado) === $estado)>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>

                        <label class="form-label" for="foto">Foto</label>
                        <img id="previewFoto" src="{{ $vehiculo->foto_url ?? 'https://placehold.co/600x400?text=Sin+foto' }}"
                             class="img-fluid rounded mb-2 w-100 object-fit-cover" style="max-height: 220px" alt="">
                        <input type="file" id="foto" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
                        <div class="form-text">JPG, PNG o WEBP. Máx 4 MB.</div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary bg-brand border-0"><i class="bi bi-save me-1"></i> Guardar</button>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.getElementById('foto').addEventListener('change', e => {
            const [archivo] = e.target.files;
            if (archivo) document.getElementById('previewFoto').src = URL.createObjectURL(archivo);
        });

        // Sugerencias de modelos desde la API pública de la NHTSA (pasando por nuestro backend)
        document.getElementById('btnSugerir').addEventListener('click', async () => {
            const marcaId = document.getElementById('marca_id').value;
            const ayuda = document.getElementById('ayudaModelos');
            if (!marcaId) { ayuda.textContent = 'Primero selecciona una marca.'; return; }

            ayuda.textContent = 'Consultando servicio externo…';
            try {
                const res = await fetch(`{{ url('marcas') }}/${marcaId}/modelos-externos`, { headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message);
                document.getElementById('sugerenciasModelos').innerHTML = json.modelos.map(m => `<option value="${m}">`).join('');
                ayuda.textContent = `${json.modelos.length} modelos sugeridos para ${json.marca}. Escribe en el campo para verlos.`;
            } catch (err) {
                ayuda.textContent = 'No se pudo consultar el servicio: ' + err.message;
            }
        });
    </script>
@endpush
