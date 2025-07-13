<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WeatherLog extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
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
