<?php

namespace Database\Factories;

use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceListItem>
 */
class PriceListItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price_list_id' => PriceList::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
