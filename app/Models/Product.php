<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'base_price'];

    const CACHE_LIST_KEY_PREFIX = 'cash_key_list_';

    public function priceListItems() {
        return $this->hasMany(PriceListItem::class);
    }
}
