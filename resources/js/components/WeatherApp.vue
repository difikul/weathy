<template>
  <div class="flex h-screen" id="app-root">
    <aside class="w-80 bg-gray-100 p-4 overflow-y-auto space-y-4">
      <h1 class="text-xl font-bold mb-2">{{ trans.title }}</h1>
      <SearchLocation />
      <WeatherLayers />
      <StatsPanel />
      <div v-if="weather" class="space-y-1">
        <h2 class="font-semibold text-lg">{{ weather.name }}</h2>
        <p>{{ weather.weather[0].description }}</p>
        <p class="font-bold">{{ weather.main.temp }}°C</p>
      </div>
    </aside>
    <MapView class="flex-1" />
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useWeatherStore } from '../store/weather';
import SearchLocation from './SearchLocation.vue';
import WeatherLayers from './WeatherLayers.vue';
import MapView from './MapView.vue';
import StatsPanel from './StatsPanel.vue';
import { onMounted } from 'vue';

const store = useWeatherStore();
const { current: weather } = storeToRefs(store);
const trans = window.trans;

onMounted(() => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      pos => store.loadByCoords(pos.coords.latitude, pos.coords.longitude),
      () => store.loadByCoords(49.1951, 16.6068)
    );
  } else {
    store.loadByCoords(49.1951, 16.6068);
  }
});
</script>
