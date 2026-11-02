<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    public function index(): View
    {
        return view('vehiculos.index', [
            'vehiculos' => Vehiculo::with('marca')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('vehiculos.form', [
            'vehiculo' => new Vehiculo(['estado' => 'disponible']),
            'marcas' => Marca::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['user_id'] = $request->user()->id;

        if ($request->hasFile('foto')) {
            $datos['foto'] = $request->file('foto')->store('vehiculos', 'public');
        }

        Vehiculo::create($datos);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado.');
    }

    public function show(Vehiculo $vehiculo): View
    {
        return view('vehiculos.show', ['vehiculo' => $vehiculo->load('marca', 'registradoPor')]);
    }

    public function edit(Vehiculo $vehiculo): View
    {
        return view('vehiculos.form', [
            'vehiculo' => $vehiculo,
            'marcas' => Marca::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $datos = $this->validar($request, $vehiculo);

        if ($request->hasFile('foto')) {
            if ($vehiculo->foto) {
                Storage::disk('public')->delete($vehiculo->foto);
            }
            $datos['foto'] = $request->file('foto')->store('vehiculos', 'public');
        }

        $vehiculo->update($datos);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        if ($vehiculo->foto) {
            Storage::disk('public')->delete($vehiculo->foto);
        }
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado.');
    }

    public function reportePdf(): Response
    {
        $vehiculos = Vehiculo::with('marca')->orderBy('estado')->orderBy('marca_id')->get();

        $pdf = Pdf::loadView('reportes.vehiculos', [
            'vehiculos' => $vehiculos,
            'generado' => now(),
            'total' => $vehiculos->where('estado', '!=', 'vendido')->sum('precio'),
        ])->setPaper('letter', 'landscape');

        return $pdf->download('inventario-vehiculos-'.now()->format('Ymd').'.pdf');
    }

    private function validar(Request $request, ?Vehiculo $vehiculo = null): array
    {
        return $request->validate([
            'marca_id' => ['required', 'exists:marcas,id'],
            'modelo' => ['required', 'string', 'max:100'],
            'anio' => ['required', 'integer', 'between:1950,'.(date('Y') + 1)],
            'placa' => ['required', 'string', 'max:10', Rule::unique('vehiculos')->ignore($vehiculo?->id)],
            'color' => ['nullable', 'string', 'max:40'],
            'tipo' => ['required', Rule::in(Vehiculo::TIPOS)],
            'kilometraje' => ['required', 'integer', 'min:0'],
            'precio' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', Rule::in(Vehiculo::ESTADOS)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }
}
