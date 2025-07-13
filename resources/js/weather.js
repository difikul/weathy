import 'leaflet/dist/leaflet.js';

let map;
let overlay;

export async function fetchWeather(params) {
    const query = new URLSearchParams(params);
    const res = await fetch(`/api/weather?${query}`);
    return res.json();
}

export async function fetchForecast(params) {
    const query = new URLSearchParams(params);
    const res = await fetch(`/api/forecast?${query}`);
    return res.json();
}

export async function initMap(lat, lon) {
    const mapDiv = document.getElementById('map');
    if (!map) {
        map = L.map(mapDiv).setView([lat, lon], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);
    } else {
        map.setView([lat, lon], 8);
    }
}

export async function updateMapLayer(layer) {
    if (!map) return;
    if (overlay) {
        map.removeLayer(overlay);
        overlay = null;
    }
    if (layer === 'radar') {
        overlay = await addRainViewerLayer(map);
        return;
    }
    overlay = L.tileLayer(`https://tile.openweathermap.org/map/${layer}/{z}/{x}/{y}.png?appid=${window.openWeatherKey}`, {
        maxZoom: 19,
        opacity: 0.5,
    }).addTo(map);
}

export async function addRainViewerLayer(map) {
    try {
        const res = await fetch('https://api.rainviewer.com/public/weather-maps.json');
        const json = await res.json();
        const past = json.radar.past;
        if (!past || !past.length) return;
        const last = past[past.length - 1];
        const url = `${json.host}${last.path}/512/{z}/{x}/{y}/2/1_1.png`;
        const layer = L.tileLayer(url, { maxZoom: 12, opacity: 0.6 });
        layer.addTo(map);
        return layer;
    } catch (e) {
        console.error('RainViewer layer failed', e);
    }
}

export function setupTheme(buttonId) {
    const btn = document.getElementById(buttonId);
    const body = document.body;
    const stored = localStorage.getItem('theme');
    if (stored === 'dark') body.classList.add('dark');
    if (btn) {
        btn.addEventListener('click', () => {
            body.classList.toggle('dark');
            localStorage.setItem('theme', body.classList.contains('dark') ? 'dark' : 'light');
        });
    }
}
