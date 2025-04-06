<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Currency;
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
            'country_id' => Country::inRandomOrder()->first()->id || null,
            'currency_id' => Currency::inRandomOrder()->first()->id,
            'start_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'end_date' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'priority' => $this->faker->numberBetween(1, 50),
        ];
    }

    public function noCountry() {
        return $this->state(function (array $attributes) {
            return [
                'country_id' => null,
            ];
        });
    }

    public function noCurrency() {
        return $this->state(function (array $attributes) {
            return [
                'currency_id' => null,
            ];
        });
    }

    public function noCountryAndCurrency() {
        return $this->state(function (array $attributes) {
            return [
                'country_id' => null,
                'currency_id' => null,
            ];
        });
    }
}
