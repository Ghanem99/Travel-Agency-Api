<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts;
use Illuminate\Database\Eloquent\Concerns;

class Travel extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'travels';

    protected $fillable = [
        'name',
        'description',
        'is_public',
        'number_of_days',
        'slug'
    ];

    public function tours() 
    {
        return $this->hasMany(Tour::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // get for the number of nights
    public function numberOfNightsAttribute() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attribute) => $attribute['number_of_days'] - 1,
        );
    }

    
}
