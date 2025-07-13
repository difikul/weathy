<template>
  <div class="space-y-2">
    <label class="block mb-1">{{ trans.map_layer }}</label>
    <select v-model="layer" @change="change" class="w-full border rounded px-2 py-1">
      <option value="precipitation_new">{{ trans.layer_precipitation }}</option>
      <option value="temp_new">{{ trans.layer_temperature }}</option>
      <option value="wind_new">{{ trans.layer_wind }}</option>
      <option value="clouds_new">{{ trans.layer_clouds }}</option>
      <option value="pressure_new">{{ trans.layer_pressure }}</option>
      <option value="radar">{{ trans.layer_radar }}</option>
    </select>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useWeatherStore } from '../store/weather';

const store = useWeatherStore();
const layer = ref(store.layer);
const trans = window.trans;

function change() {
  store.setLayer(layer.value);
}
</script>
