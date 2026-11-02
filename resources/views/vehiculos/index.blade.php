@extends('layouts.app')

@section('title', 'Vehículos')
@section('header', 'Vehículos')
@section('actions')
    <a href="{{ route('reportes.vehiculos') }}" class="btn btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary bg-brand border-0"><i class="bi bi-plus-lg me-1"></i> Nuevo vehículo</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="tablaVehiculos" class="table table-hover align-middle w-100 text-nowrap">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Tipo</th>
                        <th>Km</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vehiculos as $v)
                        <tr>
                            <td><img src="{{ $v->foto_url ?? 'https://placehold.co/128x88?text=Sin+foto' }}" class="thumb" alt=""></td>
                            <td class="fw-semibold">{{ $v->placa }}</td>
                            <td>{{ $v->marca->nombre }}</td>
                            <td>{{ $v->modelo }}</td>
                            <td>{{ $v->anio }}</td>
                            <td>{{ ucfirst($v->tipo) }}</td>
                            <td data-order="{{ $v->kilometraje }}">{{ number_format($v->kilometraje, 0, ',', '.') }}</td>
                            <td data-order="{{ $v->precio }}">$ {{ number_format($v->precio, 0, ',', '.') }}</td>
                            <td>@include('vehiculos._estado', ['estado' => $v->estado])</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('vehiculos.show', $v) }}" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('vehiculos.edit', $v) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('vehiculos.destroy', $v) }}" class="d-inline form-eliminar">
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
        new DataTable('#tablaVehiculos', { ...window.dtEspanol, columnDefs: [{ targets: [0, 9], orderable: false }], order: [], scrollX: true });
    </script>
@endpush
