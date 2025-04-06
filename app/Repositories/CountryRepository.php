<?php 

namespace App\Repositories;

use App\Models\Country;

class CountryRepository {
    public function getByCode($countryCode) {
        return Country::where('code', $countryCode)->first();
    }
}