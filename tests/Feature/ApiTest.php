<?php

namespace Tests\Feature;

use App\Models\Marca;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_sin_token_responde_401(): void
    {
        $this->getJson('/api/vehiculos')->assertUnauthorized();
        $this->get('/api/user')->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_login_devuelve_token(): void
    {
        $user = User::factory()->create(['password' => 'Clave12345']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'mala'])->assertUnauthorized();
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'Clave12345'])
            ->assertOk()
            ->assertJsonStructure(['token_type', 'token', 'user' => ['id', 'email']]);
    }

    public function test_crud_completo_con_bearer_token(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;
        $marca = Marca::factory()->create();
        $cabeceras = ['Authorization' => "Bearer {$token}"];

        // POST
        $id = $this->postJson('/api/vehiculos', [
            'marca_id' => $marca->id, 'modelo' => 'Kicks', 'anio' => 2023, 'placa' => 'API001',
            'tipo' => 'suv', 'kilometraje' => 100, 'precio' => 90000000,
        ], $cabeceras)->assertCreated()->json('id');

        // GET
        $this->getJson('/api/vehiculos', $cabeceras)->assertOk()->assertJsonPath('total', 1);
        $this->getJson("/api/vehiculos/{$id}", $cabeceras)->assertOk()->assertJsonPath('placa', 'API001');

        // PUT
        $this->putJson("/api/vehiculos/{$id}", ['precio' => 85000000, 'estado' => 'reservado'], $cabeceras)
            ->assertOk()->assertJsonPath('estado', 'reservado');

        // DELETE
        $this->deleteJson("/api/vehiculos/{$id}", [], $cabeceras)->assertNoContent();
        $this->assertDatabaseMissing('vehiculos', ['id' => $id]);
    }

    public function test_validacion_devuelve_422(): void
    {
        $token = User::factory()->create()->createToken('test')->plainTextToken;

        $this->postJson('/api/vehiculos', ['modelo' => 'X'], ['Authorization' => "Bearer {$token}"])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['marca_id', 'anio', 'placa', 'precio']);
    }

    public function test_marcas_es_publica(): void
    {
        Vehiculo::factory()->create();

        $this->getJson('/api/marcas')->assertOk()->assertJsonCount(1);
    }
}
