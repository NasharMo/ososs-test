<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryCurrency;
use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $countries = json_decode(file_get_contents(database_path('data/countries_api.json')), true);

        foreach ($countries as $country) {

            $countryModel = Country::updateOrCreate(
                ['name' => $country['name']['common']],
                ['code' => $country['cca2']]
            );

            if (isset($country['currencies']) && !empty($country['currencies'])) {

                foreach ($country['currencies'] as $code => $currency) {
                    $currency =   Currency::updateOrCreate(
                        ['code' => $code],
                        ['name' => $currency['name'] . ' (' . $currency['symbol'] . ')']
                    );
                }
            }
        }
    }
}
