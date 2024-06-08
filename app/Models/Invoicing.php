<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicing extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*public function driver(){
        return $this->belongsTo(Driver::class);
    }*/

    public function transfer(){
        return $this->belongsTo(Transfer::class);
    }

}
