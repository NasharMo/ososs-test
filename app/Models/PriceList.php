<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceList extends Model
{
    use HasFactory;
    
    protected $fillable = ['country_id', 'currency_id', 'start_date', 'end_date', 'priority'];
    
    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function items() {
        return $this->hasMany(PriceListItem::class);
    }
}
