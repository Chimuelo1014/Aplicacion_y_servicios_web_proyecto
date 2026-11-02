<?php

namespace Database\Factories;

use App\Models\Marca;
use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehiculo>
 */
class VehiculoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'marca_id' => Marca::factory(),
            'modelo' => fake()->randomElement(['Sport', 'Comfort', 'GT', 'LX', 'Plus', 'Limited']),
            'anio' => fake()->numberBetween(2010, (int) date('Y')),
            'placa' => strtoupper(fake()->unique()->bothify('???###')),
            'color' => fake()->randomElement(['Blanco', 'Negro', 'Gris', 'Rojo', 'Azul', 'Plata']),
            'tipo' => fake()->randomElement(Vehiculo::TIPOS),
            'kilometraje' => fake()->numberBetween(0, 180000),
            'precio' => fake()->numberBetween(25, 250) * 1000000,
            'estado' => fake()->randomElement(['disponible', 'disponible', 'disponible', 'reservado', 'vendido']),
            'descripcion' => fake()->sentence(12),
        ];
    }
}
