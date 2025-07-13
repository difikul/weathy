<?php

namespace App\Console\Commands;

use App\Models\WeatherLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
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

        $dates = iterator_to_array($period);
        $total = count($dates);

        $saved = 0;
        $skipped = 0;

        $client = new Client(['base_uri' => 'https://archive-api.open-meteo.com']);

        $this->output->progressStart($total);

        $requests = function () use ($dates, $lat, $lon, $client) {
            foreach ($dates as $date) {
                yield function () use ($client, $date, $lat, $lon) {
                    return $client->getAsync('/v1/archive', [
                        'query' => [
                            'latitude' => $lat,
                            'longitude' => $lon,
                            'start_date' => $date->toDateString(),
                            'end_date' => $date->toDateString(),
                            'hourly' => 'temperature_2m,relative_humidity_2m,windspeed_10m,pressure_msl,precipitation',
                            'timezone' => 'UTC',
                        ],
                        'timeout' => 10,
                    ]);
                };
            }
        };

        $indexToDate = array_values($dates);

        $pool = new Pool($client, $requests(), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) use (&$saved, &$skipped, $indexToDate, $lat, $lon, $source) {
                $date = $indexToDate[$index];
                if (WeatherLog::where('source', $source)->whereDate('timestamp', $date->toDateString())->exists()) {
                    $skipped++;
                    $this->output->progressAdvance();
                    return;
                }

                $json = json_decode($response->getBody()->getContents(), true);
                $hourly = $json['hourly'] ?? null;
                if (!is_array($hourly) || empty($hourly['time'])) {
                    $skipped++;
                    $this->output->progressAdvance();
                    return;
                }

                $count = count($hourly['time']);
                if ($count === 0) {
                    $skipped++;
                    $this->output->progressAdvance();
                    return;
                }

                WeatherLog::create([
                    'id' => (string) Str::uuid(),
                    'temperature' => round(array_sum($hourly['temperature_2m']) / $count, 2),
                    'humidity' => round(array_sum($hourly['relative_humidity_2m']) / $count),
                    'wind_speed' => round(array_sum($hourly['windspeed_10m']) / $count, 2),
                    'pressure' => round(array_sum($hourly['pressure_msl']) / $count),
                    'precipitation' => round(array_sum($hourly['precipitation']) / $count, 2),
                    'lat' => $lat,
                    'lon' => $lon,
                    'source' => $source,
                    'timestamp' => $date->startOfDay()->toDateTimeString(),
                ]);
                $saved++;
                $this->output->progressAdvance();
            },
            'rejected' => function ($reason, $index) use (&$skipped) {
                $skipped++;
                $this->output->progressAdvance();
            },
        ]);

        $pool->promise()->wait();

        $this->output->progressFinish();
        $this->newLine();
        $this->info("Processed {$total} days");
        $this->info("Saved: {$saved}");
        $this->info("Skipped (duplicates/missing): {$skipped}");

        return Command::SUCCESS;
    }

}
