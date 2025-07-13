<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-o7MgGQ8Bkzs3NLe9FPd1njO9wVLfWkxZQELRkRf6rxk=" crossorigin=""/>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/weather.js'])
    @endif
</head>
<body class="p-6">
<div class="max-w-xl mx-auto space-y-4">
    <h1 class="text-2xl font-bold">Weather Tracker</h1>
    <div>
        <input id="city" type="text" placeholder="Enter city" class="border p-2 w-2/3" />
        <button id="search" class="bg-blue-500 text-white px-4 py-2">Get Weather</button>
        <button id="current" class="bg-green-500 text-white px-4 py-2">Use My Location</button>
    </div>
    <div id="weather" class="p-4 border rounded"></div>
    <div id="map" class="h-64"></div>
    @if($locations->count())
        <h2 class="text-xl font-semibold mt-6">Recent Searches</h2>
        <ul>
            @foreach($locations as $loc)
                <li>{{ $loc->name }} ({{ $loc->latitude }}, {{ $loc->longitude }})</li>
            @endforeach
        </ul>
    @endif
</div>
<script>
    window.openWeatherKey = "{{ $openWeatherKey }}";
</script>
</body>
</html>
