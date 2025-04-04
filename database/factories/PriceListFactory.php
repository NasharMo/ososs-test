<?php

namespace Database\Factories;

use App\Models\CountryCurrency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceList>
 */
class PriceListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_currency_id' => CountryCurrency::inRandomOrder()->first()->id,
            'start_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'end_date' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'priority' => $this->faker->numberBetween(1, 50),
        ];
    }

    public function noCountryCurrency() {
        return $this->state(function (array $attributes) {
            return [
                'country_currency_id' => null,
            ];
        });
    }
}
