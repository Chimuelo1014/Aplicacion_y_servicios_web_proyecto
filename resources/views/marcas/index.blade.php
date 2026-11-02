@extends('layouts.app')

@section('title', 'Marcas')
@section('header', 'Marcas')
@section('actions')
    <a href="{{ route('marcas.create') }}" class="btn btn-primary bg-brand border-0"><i class="bi bi-plus-lg me-1"></i> Nueva marca</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="tablaMarcas" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nombre</th>
                        <th>País de origen</th>
                        <th>Vehículos</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($marcas as $marca)
                        <tr>
                            <td>
                                @if ($marca->logo)
                                    <img src="{{ asset('storage/'.$marca->logo) }}" class="thumb object-fit-contain bg-light" alt="">
                                @else
                                    <span class="text-muted"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $marca->nombre }}</td>
                            <td>{{ $marca->pais_origen ?? '—' }}</td>
                            <td><span class="badge text-bg-secondary">{{ $marca->vehiculos_count }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('marcas.destroy', $marca) }}" class="d-inline form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new DataTable('#tablaMarcas', { ...window.dtEspanol, columnDefs: [{ targets: [0, 4], orderable: false }], order: [[1, 'asc']] });
    </script>
@endpush
