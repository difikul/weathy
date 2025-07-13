import 'leaflet/dist/leaflet.js';

function showWeather(data) {
    const card = document.getElementById('weather');
    const box = card.querySelector('.card-body');
    card.style.display = 'block';
    if (data.weather) {
        box.innerHTML = `<h5 class="card-title">${data.name}</h5>` +
            `<p class="card-text">${data.weather[0].description}</p>` +
            `<p class="card-text"><strong>${data.main.temp}&nbsp;°C</strong></p>` +
            `<p class="card-text">${window.trans.humidity}: ${data.main.humidity}%</p>` +
            `<p class="card-text">${window.trans.wind}: ${data.wind.speed} m/s</p>`;
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
    L.tileLayer(`https://tile.openweathermap.org/map/lightning_new/{z}/{x}/{y}.png?appid=${window.openWeatherKey}`, {
        maxZoom: 19,
        opacity: 0.7
    }).addTo(map);
    addRainViewerLayer(map);
}

async function addRainViewerLayer(map) {
    try {
        const res = await fetch('https://api.rainviewer.com/public/weather-maps.json');
        const json = await res.json();
        const past = json.radar.past;
        if (!past || !past.length) return;
        const last = past[past.length - 1];
        const url = `${json.host}${last.path}/512/{z}/{x}/{y}/2/1_1.png`;
        L.tileLayer(url, { maxZoom: 12, opacity: 0.6 }).addTo(map);
    } catch (e) {
        console.error('RainViewer layer failed', e);
    }
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
