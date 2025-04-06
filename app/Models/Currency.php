<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    const DEFAULT_CURRENCY_CODE = 'USD';

    public function priceLists() {
        return $this->hasMany(PriceList::class);
    }
}
