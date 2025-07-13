<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WeatherLog extends Model
{
    use HasUuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'temperature',
        'humidity',
        'wind_speed',
        'pressure',
        'precipitation',
        'lat',
        'lon',
        'source',
        'timestamp',
    ];
}
