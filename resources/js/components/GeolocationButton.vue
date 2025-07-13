<template>
  <button @click="locate" class="bg-green-500 text-white px-3 py-1 rounded">
    {{ trans.use_location }}
  </button>
</template>

<script setup>
import { useWeatherStore } from '../store/weather';

const trans = window.trans;
const store = useWeatherStore();

function locate() {
  if (!navigator.geolocation) {
    alert('Geolocation not supported');
    return;
  }
  navigator.geolocation.getCurrentPosition(
    pos => store.loadByCoords(pos.coords.latitude, pos.coords.longitude),
    err => alert(err.message)
  );
}
</script>
