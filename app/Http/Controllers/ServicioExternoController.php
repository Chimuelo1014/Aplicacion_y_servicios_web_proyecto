<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

/**
 * Integración con servicios web externos desde el servidor (Http client de Laravel).
 */
class ServicioExternoController extends Controller
{
    /**
     * Pantalla que consume NUESTRA API REST con token Bearer (GET, POST, PUT, DELETE) desde el navegador.
     */
    public function clienteApi(): View
    {
        return view('api.cliente', ['marcas' => Marca::orderBy('nombre')->get(['id', 'nombre'])]);
    }

    /**
     * Consulta los modelos de una marca en la API pública de la NHTSA (vPIC).
     */
    public function modelosPorMarca(Marca $marca): JsonResponse
    {
        $modelos = Cache::remember('nhtsa_modelos_'.$marca->id, now()->addDay(), function () use ($marca) {
            $respuesta = Http::baseUrl(config('services.nhtsa.url'))
                ->acceptJson()
                ->withHeaders(['User-Agent' => config('app.name')])
                ->timeout(10)
                ->get('/vehicles/GetModelsForMake/'.rawurlencode($marca->nombre), ['format' => 'json']);

            if ($respuesta->failed()) {
                return null;
            }

            return collect($respuesta->json('Results', []))
                ->pluck('Model_Name')
                ->unique()
                ->sort()
                ->values()
                ->all();
        });

        if ($modelos === null) {
            Cache::forget('nhtsa_modelos_'.$marca->id);

            return response()->json(['message' => 'El servicio externo no respondió.'], 502);
        }

        return response()->json(['marca' => $marca->nombre, 'modelos' => $modelos]);
    }
}
