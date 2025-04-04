<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCurrency extends Model
{
    use HasFactory;
    
    protected $fillable =['country_id', 'currency_id'];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function priceLists()
    {
        return $this->hasMany(PriceList::class, 'country_currency_id');
    }

}
