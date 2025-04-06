<?php

namespace Database\Seeders;

use App\Models\PriceList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceList::factory(3)->noCountry()->create();
        PriceList::factory(3)->noCurrency()->create();
        PriceList::factory(3)->noCountryAndCurrency()->create();
        PriceList::factory(10)->create();
    }
}
