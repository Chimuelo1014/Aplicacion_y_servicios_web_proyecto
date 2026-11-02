<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MarcaController extends Controller
{
    public function index(): View
    {
        return view('marcas.index', [
            'marcas' => Marca::withCount('vehiculos')->orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('marcas.form', ['marca' => new Marca]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);

        if ($request->hasFile('logo')) {
            $datos['logo'] = $request->file('logo')->store('marcas', 'public');
        }

        Marca::create($datos);

        return redirect()->route('marcas.index')->with('success', 'Marca creada.');
    }

    public function edit(Marca $marca): View
    {
        return view('marcas.form', compact('marca'));
    }

    public function update(Request $request, Marca $marca): RedirectResponse
    {
        $datos = $this->validar($request, $marca);

        if ($request->hasFile('logo')) {
            if ($marca->logo) {
                Storage::disk('public')->delete($marca->logo);
            }
            $datos['logo'] = $request->file('logo')->store('marcas', 'public');
        }

        $marca->update($datos);

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada.');
    }

    public function destroy(Marca $marca): RedirectResponse
    {
        if ($marca->vehiculos()->exists()) {
            return back()->with('error', 'No se puede eliminar una marca que tiene vehículos registrados.');
        }

        if ($marca->logo) {
            Storage::disk('public')->delete($marca->logo);
        }
        $marca->delete();

        return redirect()->route('marcas.index')->with('success', 'Marca eliminada.');
    }

    private function validar(Request $request, ?Marca $marca = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:80', Rule::unique('marcas')->ignore($marca?->id)],
            'pais_origen' => ['nullable', 'string', 'max:80'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);
    }
}
