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
        fetchForecast({lat: json.coord.lat, lon: json.coord.lon});
    }
}

async function fetchForecast(params) {
    const query = new URLSearchParams(params);
    const res = await fetch(`/api/forecast?${query}`);
    const json = await res.json();
    showForecast(json);
}

function showForecast(data) {
    const card = document.getElementById('forecast');
    const box = card.querySelector('.card-body');
    if (!data.list) {
        card.style.display = 'none';
        return;
    }
    card.style.display = 'block';
    const days = {};
    data.list.forEach(item => {
        const day = item.dt_txt.split(' ')[0];
        days[day] = days[day] || [];
        days[day].push(item);
    });
    let html = `<h5 class="card-title">${window.trans.forecast}</h5>`;
    html += '<div class="d-flex flex-wrap gap-3">';
    Object.keys(days).slice(0,5).forEach(day => {
        const avg = days[day].reduce((acc, d) => acc + d.main.temp, 0) / days[day].length;
        html += `<div><div class="fw-semibold">${day}</div><div>${avg.toFixed(1)}°C</div></div>`;
    });
    html += '</div>';
    box.innerHTML = html;
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

function setupTheme() {
    const btn = document.getElementById('theme-toggle');
    const body = document.body;
    const stored = localStorage.getItem('theme');
    if (stored === 'dark') body.classList.add('dark');
    btn.addEventListener('click', () => {
        body.classList.toggle('dark');
        localStorage.setItem('theme', body.classList.contains('dark') ? 'dark' : 'light');
    });
}

setupTheme();
