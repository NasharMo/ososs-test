<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceListItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['price_list_id', 'product_id', 'price'];

    public function priceList() {
        return $this->belongsTo(PriceList::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
