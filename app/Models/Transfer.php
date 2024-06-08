<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function car(){
        return $this->belongsTo(Car::class);
    }

    public function invoicings(){
        return $this->hasOne(Invoicing::class);
    }
}
