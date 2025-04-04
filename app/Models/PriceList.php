<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceList extends Model
{
    use HasFactory;
    
    protected $fillable = ['country_currency_id', 'start_date', 'end_date', 'priority'];
    
    public function countryCurrencies()
    {
        return $this->belongsTo(CountryCurrency::class, 'country_currency_id');
    }


    public function priceListItems() 
    {
        return $this->hasMany(PriceListItem::class);
    }
}
