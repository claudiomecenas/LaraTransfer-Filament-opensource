<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [];

    //create hasmany relationship
    public function cars(){
        return $this->hasMany(Car::class);
    } 

    /*public function invoicings(){
        return $this->hasMany(Invoicing::class);
    }*/

}
