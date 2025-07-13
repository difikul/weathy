import 'leaflet/dist/leaflet.js';

let map;
let overlay;
let rainFrames = [];
let rainHost = '';
let currentFrame = 0;

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

export async function fetchCombined(params) {
    const query = new URLSearchParams(params);
    const res = await fetch(`/api/combined?${query}`);
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
        await loadRainViewerFrames();
        return setRadarFrame(rainFrames.length - 1);
    }
    overlay = L.tileLayer(`https://tile.openweathermap.org/map/${layer}/{z}/{x}/{y}.png?appid=${window.openWeatherKey}`, {
        maxZoom: 19,
        opacity: 0.5,
    }).addTo(map);
}

async function loadRainViewerFrames() {
    if (rainFrames.length) return;
    try {
        const res = await fetch('https://api.rainviewer.com/public/weather-maps.json');
        const json = await res.json();
        rainFrames = json.radar.past || [];
        rainHost = json.host;
    } catch (e) {
        console.error('RainViewer frames failed', e);
    }
}

export function getRainViewerFrameCount() {
    return rainFrames.length;
}

export function setRadarFrame(index) {
    if (!map || !rainFrames.length) return;
    if (index < 0 || index >= rainFrames.length) return;
    if (overlay) {
        map.removeLayer(overlay);
        overlay = null;
    }
    const frame = rainFrames[index];
    const url = `${rainHost}${frame.path}/512/{z}/{x}/{y}/2/1_1.png`;
    overlay = L.tileLayer(url, { maxZoom: 12, opacity: 0.6 });
    overlay.addTo(map);
    currentFrame = index;
    return overlay;
}
