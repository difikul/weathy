<?php

namespace App\Console\Commands;

use App\Models\WeatherLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportWeatherHistory extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'weather:import-history {--from=} {--to=}';

    /**
     * The console command description.
     */
    protected $description = 'Import historical weather data for Brno from Open-Meteo';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lat = 49.1951;
        $lon = 16.6068;
        $source = 'open-meteo';

        $from = $this->option('from') ? Carbon::parse($this->option('from')) : Carbon::create(2000, 1, 1);
        $to = $this->option('to') ? Carbon::parse($this->option('to')) : Carbon::today();

        if ($from->greaterThan($to)) {
            $this->error('--from must be before --to');
            return Command::FAILURE;
        }

        $period = new CarbonPeriod($from, $to);
        $saved = 0;
        $skipped = 0;

        $this->withProgressBar($period, function (Carbon $date) use (&$saved, &$skipped, $lat, $lon, $source) {
            if (WeatherLog::where('source', $source)->whereDate('timestamp', $date->toDateString())->exists()) {
                $skipped++;
                return;
            }

            $data = $this->fetchDay($date, $lat, $lon);
            if ($data === null) {
                $skipped++;
                return;
            }

            WeatherLog::create(array_merge($data, [
                'id' => (string) Str::uuid(),
                'lat' => $lat,
                'lon' => $lon,
                'source' => $source,
                'timestamp' => $date->startOfDay()->toDateTimeString(),
            ]));
            $saved++;
            sleep(1);
        });

        $total = $period->count();
        $this->newLine();
        $this->info("Processed {$total} days");
        $this->info("Saved: {$saved}");
        $this->info("Skipped (duplicates/missing): {$skipped}");

        return Command::SUCCESS;
    }

    protected function fetchDay(Carbon $date, float $lat, float $lon): ?array
    {
        $response = Http::baseUrl('https://archive-api.open-meteo.com')
            ->get('/v1/archive', [
                'latitude' => $lat,
                'longitude' => $lon,
                'start_date' => $date->toDateString(),
                'end_date' => $date->toDateString(),
                'hourly' => 'temperature_2m,relative_humidity_2m,windspeed_10m,pressure_msl,precipitation',
                'timezone' => 'UTC',
            ]);

        if (!$response->successful()) {
            return null;
        }

        $hourly = $response->json('hourly');
        if (!is_array($hourly) || empty($hourly['time'])) {
            return null;
        }

        $count = count($hourly['time']);
        if ($count === 0) {
            return null;
        }

        return [
            'temperature' => round(array_sum($hourly['temperature_2m']) / $count, 2),
            'humidity' => round(array_sum($hourly['relative_humidity_2m']) / $count),
            'wind_speed' => round(array_sum($hourly['windspeed_10m']) / $count, 2),
            'pressure' => round(array_sum($hourly['pressure_msl']) / $count),
            'precipitation' => round(array_sum($hourly['precipitation']) / $count, 2),
        ];
    }
}
