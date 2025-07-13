<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Location model representing a stored user location.
 */
class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];
}
