<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeatherLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary()
    {
        $week = WeatherLog::where('timestamp', '>=', now()->subDays(7))
            ->selectRaw('AVG(temperature) as temperature, AVG(humidity) as humidity, AVG(pressure) as pressure, AVG(wind_speed) as wind_speed, AVG(precipitation) as precipitation')
            ->first();
        $month = WeatherLog::where('timestamp', '>=', now()->subDays(30))
            ->selectRaw('AVG(temperature) as temperature, AVG(humidity) as humidity, AVG(pressure) as pressure, AVG(wind_speed) as wind_speed, AVG(precipitation) as precipitation')
            ->first();

        return response()->json([
            'week' => [
                'temperature' => $week ? round((float) $week->temperature, 1) : null,
                'humidity' => $week ? round((float) $week->humidity) : null,
                'pressure' => $week ? round((float) $week->pressure) : null,
                'wind_speed' => $week ? round((float) $week->wind_speed, 1) : null,
                'precipitation' => $week ? round((float) $week->precipitation, 1) : null,
            ],
            'month' => [
                'temperature' => $month ? round((float) $month->temperature, 1) : null,
                'humidity' => $month ? round((float) $month->humidity) : null,
                'pressure' => $month ? round((float) $month->pressure) : null,
                'wind_speed' => $month ? round((float) $month->wind_speed, 1) : null,
                'precipitation' => $month ? round((float) $month->precipitation, 1) : null,
            ],
        ]);
    }

    public function extremes()
    {
        $start = now()->subDays(30);
        $maxTemp = WeatherLog::where('timestamp', '>=', $start)->orderByDesc('temperature')->first();
        $minTemp = WeatherLog::where('timestamp', '>=', $start)->orderBy('temperature')->first();
        $maxHumidity = WeatherLog::where('timestamp', '>=', $start)->orderByDesc('humidity')->first();
        $minHumidity = WeatherLog::where('timestamp', '>=', $start)->orderBy('humidity')->first();

        return response()->json([
            'max_temperature' => $maxTemp ? ['value' => $maxTemp->temperature, 'time' => $maxTemp->timestamp] : null,
            'min_temperature' => $minTemp ? ['value' => $minTemp->temperature, 'time' => $minTemp->timestamp] : null,
            'max_humidity' => $maxHumidity ? ['value' => $maxHumidity->humidity, 'time' => $maxHumidity->timestamp] : null,
            'min_humidity' => $minHumidity ? ['value' => $minHumidity->humidity, 'time' => $minHumidity->timestamp] : null,
        ]);
    }

    public function prediction()
    {
        $logs = WeatherLog::orderByDesc('timestamp')->limit(6)->get();
        if ($logs->isEmpty()) {
            return response()->json([]);
        }
        return response()->json([
            'temperature' => round($logs->avg('temperature'), 1),
            'humidity' => round($logs->avg('humidity')),
            'pressure' => round($logs->avg('pressure')),
            'precipitation' => round($logs->avg('precipitation'), 1),
        ]);
    }

    public function chart(Request $request)
    {
        $type = $request->input('type', 'temperature');
        $period = (int) $request->input('period', 30);
        $start = now()->subDays($period);

        $logs = WeatherLog::where('timestamp', '>=', $start)
            ->orderBy('timestamp')
            ->get()
            ->groupBy(fn($l) => Carbon::parse($l->timestamp)->format('Y-m-d'));

        $data = [];
        foreach ($logs as $date => $items) {
            $data[] = [
                'date' => $date,
                'value' => round($items->avg($type), 2),
            ];
        }

        return response()->json($data);
    }
}
