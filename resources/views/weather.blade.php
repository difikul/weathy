<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('weather.title') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous"/>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: var(--font-sans);
            background: radial-gradient(circle at top left, #eef3ff, #cdd8f6);
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/weather.js'])
    @endif
</head>
<body>
<div id="app"></div>
<script>
    window.openWeatherKey = "{{ $openWeatherKey }}";
    window.trans = @json(__('weather'));
    window.recentLocations = @json($locations);
</script>
</body>
</html>
