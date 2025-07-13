import 'leaflet/dist/leaflet.js';

function showWeather(data) {
    const box = document.getElementById('weather');
    if (data.weather) {
        box.innerHTML = `<p><strong>${data.name}</strong> - ${data.weather[0].description}</p>
            <p>Temperature: ${data.main.temp} °C</p>`;
    } else {
        box.textContent = data.error || 'No data';
    }
}

async function fetchWeather(params) {
    const query = new URLSearchParams(params);
    const res = await fetch(`/api/weather?${query}`);
    const json = await res.json();
    showWeather(json);
    if (json.coord) {
        initMap(json.coord.lat, json.coord.lon);
    }
}

function initMap(lat, lon) {
    const mapDiv = document.getElementById('map');
    mapDiv.innerHTML = '';
    const map = L.map(mapDiv).setView([lat, lon], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    L.tileLayer(`https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=${window.openWeatherKey}`, {
        maxZoom: 19,
        opacity: 0.5
    }).addTo(map);
}

document.getElementById('search').addEventListener('click', () => {
    const city = document.getElementById('city').value;
    if (city) fetchWeather({city});
});

document.getElementById('current').addEventListener('click', () => {
    navigator.geolocation.getCurrentPosition(pos => {
        fetchWeather({lat: pos.coords.latitude, lon: pos.coords.longitude});
    });
});
