<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost_price = fake()->randomFloat(2, 10, 10000);
        $selling_price = $cost_price * 1.2;

        return [
            'code' => strtoupper(fake()->unique()->regexify('PRD-\d{5}')),
            'name' => ucwords(fake()->unique()->words(2, true)),
            'cost_price' => $cost_price,
            'selling_price' => $selling_price,
            'quantity' => fake()->randomNumber(2),
            'description' => fake()->text(),
        ];
    }
}
