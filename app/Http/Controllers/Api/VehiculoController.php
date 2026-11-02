<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vehiculo::with('marca:id,nombre')->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        return response()->json($query->paginate($request->integer('per_page', 15)));
    }

    public function show(Vehiculo $vehiculo): JsonResponse
    {
        return response()->json($vehiculo->load('marca'));
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $this->validar($request);
        $datos['user_id'] = $request->user()->id;

        $vehiculo = Vehiculo::create($datos);

        return response()->json($vehiculo->load('marca'), 201);
    }

    public function update(Request $request, Vehiculo $vehiculo): JsonResponse
    {
        $vehiculo->update($this->validar($request, $vehiculo));

        return response()->json($vehiculo->load('marca'));
    }

    public function destroy(Vehiculo $vehiculo): JsonResponse
    {
        $vehiculo->delete();

        return response()->json(null, 204);
    }

    private function validar(Request $request, ?Vehiculo $vehiculo = null): array
    {
        // En PUT/PATCH todos los campos son opcionales: solo se valida lo que llega.
        $req = $vehiculo ? 'sometimes' : 'required';

        return $request->validate([
            'marca_id' => [$req, 'exists:marcas,id'],
            'modelo' => [$req, 'string', 'max:100'],
            'anio' => [$req, 'integer', 'between:1950,'.(date('Y') + 1)],
            'placa' => [$req, 'string', 'max:10', Rule::unique('vehiculos')->ignore($vehiculo?->id)],
            'color' => ['nullable', 'string', 'max:40'],
            'tipo' => [$req, Rule::in(Vehiculo::TIPOS)],
            'kilometraje' => [$req, 'integer', 'min:0'],
            'precio' => [$req, 'numeric', 'min:0'],
            'estado' => ['sometimes', Rule::in(Vehiculo::ESTADOS)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
