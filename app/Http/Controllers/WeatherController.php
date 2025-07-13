<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\Location;

class WeatherController extends Controller
{
    /**
     * Display the weather application page.
     */
    public function index(Request $request)
    {
        $lang = $request->query('lang', config('app.locale'));
        app()->setLocale($lang);

        $locations = Location::orderByDesc('created_at')->get();

        return view('weather', [
            'locations' => $locations,
            'openWeatherKey' => config('services.openweather.key'),
        ]);
    }

    /**
     * Return weather data for provided coordinates or city.
     */
    public function getWeather(Request $request)
    {
        $request->validate([
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'city' => 'nullable|string',
        ]);

        $lat = $request->lat;
        $lon = $request->lon;
        $city = $request->city;

        $apiKey = config('services.openweather.key');

        if ($city) {
            $geo = Http::get('https://api.openweathermap.org/geo/1.0/direct', [
                'q' => $city,
                'limit' => 1,
                'appid' => $apiKey,
            ])->json();

            if (!empty($geo[0])) {
                $lat = $geo[0]['lat'];
                $lon = $geo[0]['lon'];
                Location::create([
                    'name' => $city,
                    'latitude' => $lat,
                    'longitude' => $lon,
                ]);
            } else {
                return response()->json(['error' => 'Location not found'], 404);
            }
        }

        if ($lat === null || $lon === null) {
            return response()->json(['error' => 'Coordinates required'], 422);
        }

        $weather = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $apiKey,
        ])->json();

        return response()->json($weather);
    }

    /**
     * Return 5 day forecast for provided coordinates or city.
     */
    public function getForecast(Request $request)
    {
        $request->validate([
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'city' => 'nullable|string',
        ]);

        $lat = $request->lat;
        $lon = $request->lon;
        $city = $request->city;

        $apiKey = config('services.openweather.key');

        if ($city) {
            $geo = Http::get('https://api.openweathermap.org/geo/1.0/direct', [
                'q' => $city,
                'limit' => 1,
                'appid' => $apiKey,
            ])->json();

            if (!empty($geo[0])) {
                $lat = $geo[0]['lat'];
                $lon = $geo[0]['lon'];
            } else {
                return response()->json(['error' => 'Location not found'], 404);
            }
        }

        if ($lat === null || $lon === null) {
            return response()->json(['error' => 'Coordinates required'], 422);
        }

        $forecast = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'lat' => $lat,
            'lon' => $lon,
            'units' => 'metric',
            'appid' => $apiKey,
        ])->json();

        return response()->json($forecast);
    }
}
