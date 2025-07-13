<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('weather.title') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: radial-gradient(circle at top left, #f0f4ff, #d9e3f7);
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/weather.js'])
    @endif
</head>
<body class="py-5">
<div class="container" style="max-width: 768px;">
    <h1 class="display-5 fw-bold mb-4 text-center">{{ __('weather.title') }}</h1>
    <div class="input-group mb-3">
        <input id="city" type="text" class="form-control" placeholder="{{ __('weather.enter_city') }}">
        <button id="search" class="btn btn-primary">{{ __('weather.get_weather') }}</button>
        <button id="current" class="btn btn-success">{{ __('weather.use_location') }}</button>
    </div>
    <div id="weather" class="card mb-3" style="display:none;">
        <div class="card-body"></div>
    </div>
    <div id="map" class="mb-1" style="height: 16rem;"></div>
    <p class="text-end text-muted small mb-3">{!! __('weather.radar_credit') !!}</p>
    @if($locations->count())
        <h2 class="h4 fw-semibold">{{ __('weather.recent_searches') }}</h2>
        <ul class="list-group">
            @foreach($locations as $loc)
                <li class="list-group-item">{{ $loc->name }} ({{ $loc->latitude }}, {{ $loc->longitude }})</li>
            @endforeach
        </ul>
    @endif
</div>
<script>
    window.openWeatherKey = "{{ $openWeatherKey }}";
    window.trans = {
        humidity: "{{ __('weather.humidity') }}",
        wind: "{{ __('weather.wind') }}"
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
