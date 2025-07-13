<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\WeatherLog;
use Illuminate\Support\Str;
use App\Services\WeatherAggregator;

class WeatherController extends Controller
{
    public function __construct(protected WeatherAggregator $aggregator)
    {
    }
    /**
     * Display the weather application page.
     */
    public function index(Request $request)
    {
        app()->setLocale('cs');

        $locations = Location::orderByDesc('created_at')->get()->map(function ($loc) {
            return [
                'id' => $loc->id,
                'name' => $loc->name,
                'latitude' => $loc->latitude,
                'longitude' => $loc->longitude,
                'created_at' => $loc->created_at->locale('cs')->isoFormat('D. M. YYYY H:mm'),
            ];
        });

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

        WeatherLog::create([
            'id' => (string) Str::uuid(),
            'temperature' => $weather['main']['temp'] ?? 0,
            'humidity' => $weather['main']['humidity'] ?? 0,
            'wind_speed' => $weather['wind']['speed'] ?? 0,
            'pressure' => $weather['main']['pressure'] ?? 0,
            'precipitation' => $weather['rain']['1h'] ?? ($weather['snow']['1h'] ?? 0),
            'lat' => $lat,
            'lon' => $lon,
            'source' => 'openweathermap',
            'timestamp' => now(),
        ]);

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

        $list = $forecast['list'] ?? [];
        $days = [];
        foreach ($list as $item) {
            $date = \Carbon\Carbon::createFromTimestamp($item['dt'])->locale('cs');
            $dayKey = $date->toDateString();
            $days[$dayKey][] = $item;
        }
        $result = [];
        foreach ($days as $day => $items) {
            $date = \Carbon\Carbon::parse($day)->locale('cs');
            $avg = collect($items)->avg(fn ($d) => $d['main']['temp']);
            $result[] = [
                'date' => $date->isoFormat('dddd D. M.'),
                'temp' => round($avg, 1),
            ];
        }

        return response()->json($result);
    }

    /**
     * Return combined weather data from multiple APIs.
     */
    public function combined(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $data = $this->aggregator->aggregate($request->lat, $request->lon);

        return response()->json($data);
    }

    /**
     * Return average weather statistics for the last 24 hours.
     */
    public function stats()
    {
        $avg = WeatherLog::where('timestamp', '>=', now()->subDay())
            ->selectRaw('AVG(temperature) as temperature, AVG(humidity) as humidity, AVG(wind_speed) as wind_speed, AVG(pressure) as pressure, AVG(precipitation) as precipitation')
            ->first();

        return response()->json($avg ?? []);
    }

    /**
     * Naive prediction for the next hour based on recent measurements.
     */
    public function predict()
    {
        $logs = WeatherLog::orderByDesc('timestamp')->limit(6)->get();
        if ($logs->isEmpty()) {
            return response()->json([]);
        }

        return response()->json([
            'temperature' => round($logs->avg('temperature'), 1),
            'humidity' => round($logs->avg('humidity')),
            'wind_speed' => round($logs->avg('wind_speed'), 1),
            'pressure' => round($logs->avg('pressure')),
            'precipitation' => round($logs->avg('precipitation'), 1),
        ]);
    }
}
