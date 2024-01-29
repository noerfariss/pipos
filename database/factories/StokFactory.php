<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipe' => fake()->numberBetween(1,3),
            'suplier_id' => fake()->numberBetween(1, 7),
            'produk_id' => fake()->numberBetween(1, 25),
            'qty' => fake()->numberBetween(7, 89),
            'keterangan' => fake()->sentence(7),
        ];
    }
}
