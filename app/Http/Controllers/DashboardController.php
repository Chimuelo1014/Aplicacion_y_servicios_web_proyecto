<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Vehiculo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $porEstado = Vehiculo::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $porMarca = Marca::withCount('vehiculos')
            ->orderByDesc('vehiculos_count')
            ->get(['id', 'nombre']);

        return view('dashboard', [
            'totalVehiculos' => Vehiculo::count(),
            'totalMarcas' => Marca::count(),
            'disponibles' => $porEstado['disponible'] ?? 0,
            'vendidos' => $porEstado['vendido'] ?? 0,
            'valorInventario' => Vehiculo::where('estado', '!=', 'vendido')->sum('precio'),
            'chartEstados' => [
                'labels' => array_map('ucfirst', Vehiculo::ESTADOS),
                'data' => array_map(fn ($e) => $porEstado[$e] ?? 0, Vehiculo::ESTADOS),
            ],
            'chartMarcas' => [
                'labels' => $porMarca->pluck('nombre'),
                'data' => $porMarca->pluck('vehiculos_count'),
            ],
            'ultimos' => Vehiculo::with('marca')->latest()->take(5)->get(),
        ]);
    }
}
