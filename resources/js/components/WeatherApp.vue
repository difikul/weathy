<template>
  <div class="flex h-screen" id="app-root">
    <aside class="w-80 bg-gray-100 p-4 overflow-y-auto">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">{{ trans.title }}</h1>
      </div>
      <div class="space-y-2 mb-3">
        <input v-model="city" @keyup.enter="search" :placeholder="trans.enter_city" class="w-full border rounded px-2 py-1" />
        <div class="flex gap-2">
          <button @click="search" class="bg-blue-500 text-white px-3 py-1 rounded">{{ trans.get_weather }}</button>
          <button @click="currentLocation" class="bg-green-500 text-white px-3 py-1 rounded">{{ trans.use_location }}</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="layer" class="block mb-1">{{ trans.map_layer }}</label>
        <select id="layer" v-model="layer" @change="changeLayer" class="w-full border rounded px-2 py-1">
          <option value="precipitation_new">{{ trans.layer_precipitation }}</option>
          <option value="temp_new">{{ trans.layer_temperature }}</option>
          <option value="wind_new">{{ trans.layer_wind }}</option>
          <option value="clouds_new">{{ trans.layer_clouds }}</option>
          <option value="pressure_new">{{ trans.layer_pressure }}</option>
          <option value="radar">{{ trans.layer_radar }}</option>
        </select>
        <div v-if="layer === 'radar' && frames" class="mt-2">
          <input type="range" :max="frames - 1" min="0" v-model.number="radarIndex" class="w-full" />
        </div>
      </div>
      <div v-if="weather" class="mb-3">
        <h2 class="font-semibold text-lg">{{ weather.name }}</h2>
        <p>{{ weather.weather[0].description }}</p>
        <p class="font-bold">{{ weather.main.temp }}°C</p>
        <p>{{ trans.humidity }}: {{ weather.main.humidity }}%</p>
        <p>{{ trans.wind }}: {{ weather.wind.speed }} m/s</p>
      </div>
      <div v-if="forecast.length" class="mb-3">
        <h2 class="font-semibold text-lg mb-1">{{ trans.forecast }}</h2>
        <div class="flex flex-wrap gap-3">
          <div v-for="day in forecast" :key="day.date">
            <div class="font-semibold">{{ day.date }}</div>
            <div>{{ day.temp }}°C</div>
          </div>
        </div>
      </div>
      <p class="text-xs text-right text-gray-500" v-html="trans.radar_credit"></p>
      <div v-if="recent.length" class="mt-3">
        <h2 class="font-semibold">{{ trans.recent_searches }}</h2>
        <ul class="list-disc pl-4">
          <li v-for="loc in recent" :key="loc.id">{{ loc.name }} – {{ loc.created_at }}</li>
        </ul>
      </div>
    </aside>
    <div id="map" class="flex-1"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useWeatherStore } from '../store/weather';
import { storeToRefs } from 'pinia';
import { initMap, updateMapLayer, getRainViewerFrameCount, setRadarFrame } from '../weather';

const city = ref('');
const layer = ref('precipitation_new');
const store = useWeatherStore();
const { current: weather, forecast } = storeToRefs(store);
const radarIndex = ref(0);
const frames = ref(0);
const recent = window.recentLocations || [];
const trans = window.trans;

async function loadWeather(params) {
  if (params.city) {
    await store.loadByCity(params.city);
  } else if (params.lat && params.lon) {
    await store.loadByCoords(params.lat, params.lon);
  }
  if (weather.value?.coord) {
    await initMap(weather.value.coord.lat, weather.value.coord.lon);
    await updateMapLayer(layer.value).then(() => {
      if (layer.value === 'radar') {
        frames.value = getRainViewerFrameCount();
        radarIndex.value = frames.value - 1;
      }
    });
  }
}

function search() {
  if (city.value) {
    loadWeather({ city: city.value });
  }
}

function currentLocation() {
  navigator.geolocation.getCurrentPosition(pos => {
    loadWeather({ lat: pos.coords.latitude, lon: pos.coords.longitude });
  });
}

function changeLayer() {
  updateMapLayer(layer.value).then(() => {
    if (layer.value === 'radar') {
      frames.value = getRainViewerFrameCount();
      radarIndex.value = frames.value - 1;
    }
  });
}

watch(radarIndex, () => {
  setRadarFrame(radarIndex.value);
});

onMounted(() => {
  updateMapLayer(layer.value).then(() => {
    if (layer.value === 'radar') {
      frames.value = getRainViewerFrameCount();
      radarIndex.value = frames.value - 1;
    }
  });
});
</script>

<style scoped>
#map {
  height: 100%;
}
</style>
