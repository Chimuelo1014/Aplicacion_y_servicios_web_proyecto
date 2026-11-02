@extends('layouts.app')

@section('title', 'Dashboard')
@section('header')
    Hola, {{ auth()->user()->name }} 👋
@endsection

@section('content')
    {{-- Indicadores --}}
    <div class="row g-3 mb-4">
        @foreach ([
            ['Vehículos', $totalVehiculos, 'bi-car-front', 'primary'],
            ['Disponibles', $disponibles, 'bi-check2-circle', 'success'],
            ['Vendidos', $vendidos, 'bi-cash-coin', 'warning'],
            ['Marcas', $totalMarcas, 'bi-tags', 'info'],
        ] as [$titulo, $valor, $icono, $color])
            <div class="col-6 col-xl-3">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon bg-{{ $color }}-subtle text-{{ $color }}"><i class="bi {{ $icono }}"></i></div>
                        <div>
                            <div class="text-muted small">{{ $titulo }}</div>
                            <div class="fs-4 fw-bold">{{ $valor }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Gráficos --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Vehículos por marca</div>
                <div class="card-body"><canvas id="chartMarcas" height="140"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Estado del inventario</div>
                <div class="card-body d-flex align-items-center justify-content-center"><canvas id="chartEstados"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Últimos registrados</span>
                    <a href="{{ route('vehiculos.index') }}" class="small">Ver todos</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($ultimos as $v)
                        <li class="list-group-item d-flex align-items-center gap-3">
                            <img src="{{ $v->foto_url ?? 'https://placehold.co/128x88?text=Sin+foto' }}" class="thumb" alt="">
                            <div class="flex-grow-1">
                                <a href="{{ route('vehiculos.show', $v) }}" class="fw-semibold text-decoration-none">{{ $v->marca->nombre }} {{ $v->modelo }}</a>
                                <div class="small text-muted">{{ $v->placa }} · {{ $v->anio }}</div>
                            </div>
                            <span class="fw-semibold">$ {{ number_format($v->precio, 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Aún no hay vehículos.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-brand text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-white-50 small">Valor del inventario (no vendido)</div>
                    <div class="fs-3 fw-bold my-2 text-break">$ {{ number_format($valorInventario, 0, ',', '.') }}</div>
                    <a href="{{ route('reportes.vehiculos') }}" class="btn btn-accent mt-auto">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Descargar informe PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('chartMarcas'), {
            type: 'bar',
            data: {
                labels: @json($chartMarcas['labels']),
                datasets: [{ label: 'Vehículos', data: @json($chartMarcas['data']), backgroundColor: '#1f3a5f', borderRadius: 6 }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: @json($chartEstados['labels']),
                datasets: [{ data: @json($chartEstados['data']), backgroundColor: ['#2a9d8f', '#f4a259', '#6c757d'] }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    </script>
@endpush
