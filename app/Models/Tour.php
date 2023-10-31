<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_id', 
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];

    public function price() : Attribute 
    {
        return Attribute::make(
            get: fn($value, $attribute) => $attribute['price'] / 100,
            set: fn($value, $attribute) => $attribute['price'] = $value * 100,
        );
    }
}
