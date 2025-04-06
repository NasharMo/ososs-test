<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository {
    public function getByCode($currencyCode) {
        return Currency::where('code', $currencyCode)->first();
    }
}