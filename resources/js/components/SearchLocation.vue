<template>
  <div class="space-y-2">
    <input v-model="city" @keyup.enter="search" :placeholder="trans.enter_city" class="w-full border rounded px-2 py-1" />
    <div class="flex gap-2">
      <button @click="search" class="bg-blue-500 text-white px-3 py-1 rounded">{{ trans.get_weather }}</button>
      <GeolocationButton />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import GeolocationButton from './GeolocationButton.vue';
import { useWeatherStore } from '../store/weather';

const store = useWeatherStore();
const city = ref('');
const trans = window.trans;

function search() {
  if (!city.value) return;
  store.loadByCity(city.value);
  const history = JSON.parse(localStorage.getItem('history') || '[]');
  history.unshift(city.value);
  localStorage.setItem('history', JSON.stringify(history.slice(0, 5)));
}
</script>
