<template>
  <div class="flex h-full" id="app-root">
    <aside class="w-80 bg-white/70 backdrop-blur p-4 overflow-y-auto space-y-4">
      <h1 class="text-xl font-bold mb-2">{{ trans.title }}</h1>
      <SearchLocation />
      <WeatherLayers />
      <div v-if="weather" class="space-y-1">
        <h2 class="font-semibold text-lg">{{ weather.name }}</h2>
        <p>{{ weather.weather[0].description }}</p>
        <p class="font-bold">{{ weather.main.temp }}°C</p>
      </div>
      <div v-if="stats.temperature" class="text-sm space-y-1">
        <h3 class="font-semibold">{{ trans.stats_last_24h }}</h3>
        <p>Temp ⌀ {{ stats.temperature.toFixed(1) }}°C</p>
        <p>{{ trans.humidity }} ⌀ {{ stats.humidity.toFixed(0) }}%</p>
      </div>
      <div v-if="prediction.temperature" class="text-sm space-y-1">
        <h3 class="font-semibold">{{ trans.prediction_next }}</h3>
        <p>{{ prediction.temperature.toFixed(1) }}°C</p>
      </div>
    </aside>
    <MapView class="flex-1" />
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import { useWeatherStore } from '../store/weather';
import SearchLocation from './SearchLocation.vue';
import WeatherLayers from './WeatherLayers.vue';
import MapView from './MapView.vue';

const store = useWeatherStore();
const { current: weather, stats, prediction } = storeToRefs(store);
const trans = window.trans;

onMounted(() => {
  store.loadByCoords(49.1951, 16.6068);
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
      store.loadByCoords(pos.coords.latitude, pos.coords.longitude);
    });
  }
});
</script>
