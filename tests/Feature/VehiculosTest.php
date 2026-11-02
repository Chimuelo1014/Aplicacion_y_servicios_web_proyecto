<?php

namespace Tests\Feature;

use App\Models\Marca;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VehiculosTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_crud_de_marcas(): void
    {
        $this->actingAs($this->user)->post('/marcas', ['nombre' => 'Ford', 'pais_origen' => 'EE. UU.'])->assertRedirect('/marcas');
        $marca = Marca::firstWhere('nombre', 'Ford');

        $this->get('/marcas')->assertOk()->assertSee('Ford');
        $this->put("/marcas/{$marca->id}", ['nombre' => 'Ford Motor'])->assertRedirect('/marcas');
        $this->assertSame('Ford Motor', $marca->fresh()->nombre);
        $this->delete("/marcas/{$marca->id}")->assertRedirect('/marcas');
        $this->assertModelMissing($marca);
    }

    public function test_no_se_elimina_una_marca_con_vehiculos(): void
    {
        $vehiculo = Vehiculo::factory()->create();

        $this->actingAs($this->user)->delete("/marcas/{$vehiculo->marca_id}")->assertSessionHas('error');
        $this->assertModelExists($vehiculo->marca);
    }

    public function test_crud_de_vehiculos_con_foto(): void
    {
        Storage::fake('public');
        $marca = Marca::factory()->create();

        $this->actingAs($this->user)->post('/vehiculos', [
            'marca_id' => $marca->id,
            'modelo' => 'Hilux',
            'anio' => 2022,
            'placa' => 'ABC123',
            'tipo' => 'pickup',
            'kilometraje' => 30000,
            'precio' => 150000000,
            'estado' => 'disponible',
            'foto' => UploadedFile::fake()->image('hilux.jpg', 800, 600),
        ])->assertRedirect('/vehiculos');

        $vehiculo = Vehiculo::firstWhere('placa', 'ABC123');
        Storage::disk('public')->assertExists($vehiculo->foto);
        $this->assertSame($this->user->id, $vehiculo->user_id);

        $this->get("/vehiculos/{$vehiculo->id}")->assertOk()->assertSee('Hilux');
        $this->put("/vehiculos/{$vehiculo->id}", [...$vehiculo->only('marca_id', 'modelo', 'anio', 'placa', 'tipo', 'kilometraje', 'precio'), 'estado' => 'vendido'])
            ->assertRedirect('/vehiculos');
        $this->assertSame('vendido', $vehiculo->fresh()->estado);

        $this->delete("/vehiculos/{$vehiculo->id}")->assertRedirect('/vehiculos');
        $this->assertModelMissing($vehiculo);
        Storage::disk('public')->assertMissing($vehiculo->foto);
    }

    public function test_dashboard_y_listados_cargan(): void
    {
        Vehiculo::factory()->count(5)->create();

        $this->actingAs($this->user);
        $this->get('/dashboard')->assertOk()->assertSee('chartMarcas');
        $this->get('/vehiculos')->assertOk()->assertSee('tablaVehiculos');
        $this->get('/marcas')->assertOk()->assertSee('tablaMarcas');
    }

    public function test_descarga_informe_pdf(): void
    {
        Vehiculo::factory()->count(3)->create();

        $respuesta = $this->actingAs($this->user)->get('/vehiculos/reporte/pdf');

        $respuesta->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $respuesta->getContent());
    }

    public function test_consulta_modelos_en_servicio_externo(): void
    {
        Http::fake(['vpic.nhtsa.dot.gov/*' => Http::response(['Results' => [['Model_Name' => 'Corolla'], ['Model_Name' => 'Hilux']]])]);
        $marca = Marca::factory()->create(['nombre' => 'Toyota']);

        $this->actingAs($this->user)->getJson("/marcas/{$marca->id}/modelos-externos")
            ->assertOk()
            ->assertJson(['marca' => 'Toyota', 'modelos' => ['Corolla', 'Hilux']]);
    }
}
