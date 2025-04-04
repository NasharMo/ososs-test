<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];


    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'country_currencies', 'country_id', 'currency_id');
    }
}
