<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function countries() {
        return $this->belongsToMany(Country::class, 'country_currencies', 'currency_id', 'country_id');
    }

}
