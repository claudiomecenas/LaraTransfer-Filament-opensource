<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Casts\Attribute;
//use Illuminate\Support\Str;

class Price extends Model
{
    use HasFactory;

    protected $guarded = [];

    //create function for convert string to float
    
    /*protected function ConvertToFloat(string $value): float
    {
        dd($value);
        return (float) str_replace(['.', ','], ['', '.'], $value);
    }

    //create asessors
    protected function Price1(): Attribute    {
        return Attribute::make(
            get: fn (string $value) => number_format($value, 2, ',', '.'),
            set: fn ($value) => $this->attributes['price1'] = $this->ConvertToFloat($value),
        );
    }
    */

    

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
