<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('weather.title') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: var(--font-sans);
            background: radial-gradient(circle at top left, #eef3ff, #cdd8f6);
        }

        body.dark {
            background: #0b1120;
            color: #e2e8f0;
        }

        body.dark .card {
            background-color: #1e293b;
        }

        body.dark #sidebar {
            background-color: #1e293b;
        }

        body.dark .bg-opacity-75 {
            background-color: rgba(30, 41, 59, 0.9) !important;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        #sidebar {
            width: 350px;
            max-width: 100%;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/weather.js'])
    @endif
</head>
<body class="d-flex">
<div id="sidebar" class="bg-white bg-opacity-75 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 fw-bold mb-0">{{ __('weather.title') }}</h1>
        <button id="theme-toggle" class="btn btn-outline-secondary" title="{{ __('weather.toggle_theme') }}">🌓</button>
    </div>
    <div class="input-group mb-3">
        <input id="city" type="text" class="form-control" placeholder="{{ __('weather.enter_city') }}">
        <button id="search" class="btn btn-primary">{{ __('weather.get_weather') }}</button>
        <button id="current" class="btn btn-success">{{ __('weather.use_location') }}</button>
    </div>
    <div class="mb-3">
        <label for="layer" class="form-label">{{ __('weather.map_layer') }}</label>
        <select id="layer" class="form-select">
            <option value="precipitation_new">{{ __('weather.layer_precipitation') }}</option>
            <option value="temp_new">{{ __('weather.layer_temperature') }}</option>
            <option value="wind_new">{{ __('weather.layer_wind') }}</option>
            <option value="clouds_new">{{ __('weather.layer_clouds') }}</option>
            <option value="pressure_new">{{ __('weather.layer_pressure') }}</option>
            <option value="radar">{{ __('weather.layer_radar') }}</option>
        </select>
    </div>
    <div id="weather" class="card mb-3" style="display:none;">
        <div class="card-body"></div>
    </div>
    <div id="forecast" class="card mb-3" style="display:none;">
        <div class="card-body"></div>
    </div>
    <p class="text-end text-muted small mb-3">{!! __('weather.radar_credit') !!}</p>
    @if($locations->count())
        <h2 class="h5 fw-semibold">{{ __('weather.recent_searches') }}</h2>
        <ul class="list-group list-group-flush">
            @foreach($locations as $loc)
                <li class="list-group-item">{{ $loc->name }} ({{ $loc->latitude }}, {{ $loc->longitude }})</li>
            @endforeach
        </ul>
    @endif
</div>
<div id="map" class="flex-fill position-relative"></div>
<script>
    window.openWeatherKey = "{{ $openWeatherKey }}";
    window.trans = {
        humidity: "{{ __('weather.humidity') }}",
        wind: "{{ __('weather.wind') }}",
        forecast: "{{ __('weather.forecast') }}",
        toggleTheme: "{{ __('weather.toggle_theme') }}"
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
