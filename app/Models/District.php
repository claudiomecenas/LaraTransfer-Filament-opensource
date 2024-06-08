<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $guarded = [];

    //create hasmany prices
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
