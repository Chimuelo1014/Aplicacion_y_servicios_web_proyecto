<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario de prueba para la demo (cambiar en producción)
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@compraventa.test',
            'password' => 'Admin12345',
        ]);

        $catalogo = [
            'Chevrolet' => ['Estados Unidos', ['Onix', 'Tracker', 'Spark GT', 'Captiva', 'Colorado']],
            'Renault' => ['Francia', ['Logan', 'Sandero', 'Duster', 'Koleos', 'Kwid']],
            'Mazda' => ['Japón', ['Mazda 2', 'Mazda 3', 'CX-30', 'CX-5', 'BT-50']],
            'Toyota' => ['Japón', ['Corolla', 'Hilux', 'Fortuner', 'Yaris', 'RAV4']],
            'Kia' => ['Corea del Sur', ['Picanto', 'Rio', 'Sportage', 'Sorento']],
            'Nissan' => ['Japón', ['Versa', 'Kicks', 'Frontier', 'X-Trail']],
            'Volkswagen' => ['Alemania', ['Gol', 'Polo', 'T-Cross', 'Amarok']],
            'Yamaha' => ['Japón', ['FZ 2.0', 'MT-03', 'XTZ 150', 'NMAX']],
        ];

        foreach ($catalogo as $nombre => [$pais, $modelos]) {
            $marca = Marca::create(['nombre' => $nombre, 'pais_origen' => $pais]);

            foreach (fake()->randomElements($modelos, min(count($modelos), fake()->numberBetween(2, 5))) as $modelo) {
                Vehiculo::factory()->create([
                    'marca_id' => $marca->id,
                    'user_id' => $admin->id,
                    'modelo' => $modelo,
                    'tipo' => $nombre === 'Yamaha' ? 'moto' : fake()->randomElement(['sedan', 'hatchback', 'suv', 'camioneta', 'pickup']),
                    'precio' => $nombre === 'Yamaha'
                        ? fake()->numberBetween(8, 35) * 1000000
                        : fake()->numberBetween(35, 220) * 1000000,
                ]);
            }
        }
    }
}
