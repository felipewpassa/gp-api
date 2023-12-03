<?php

namespace Database\Factories;

use App\Models\Category;
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
        $productName = [
            'RTX 4060',
            'RX 6750 XT',
            'RX 7600 OC',
            'B450M',
            'A520M-A PRO',
            'Z590',
            'Fury Beast',
            'Gammix D35',
            'Fury Impact',
            '4600G',
            'Ryzen 5 5500',
            'i5-12400F',
        ];

        $productDescription = [
            'Descricao aaa',
            'Descricao aab',
            'Descricao aac',
            'Descricao aba',
            'Descricao abb',
            'Descricao abc',
            'Descricao aca',
            'Descricao acb',
            'Descricao acc',
        ];

        return [
            'name' => $this->faker->randomElement($productName),
            'description' => $this->faker->randomElement($productDescription),
            'price' => $this->faker->randomFloat(2),
            'expiry_date' => $this->faker->dateTimeThisMonth(),
            'image' => $this->faker->word() . '.' . $this->faker->fileExtension(),
            'category_id' => Category::all()->random()->id,
        ];
    }
}
