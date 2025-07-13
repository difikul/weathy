<template>
  <div id="map" class="h-full w-full"></div>
</template>

<script setup>
import { onMounted, watch } from 'vue';
import { useWeatherStore } from '../store/weather';
import { storeToRefs } from 'pinia';
import { initMap, updateMapLayer } from '../weather';

const store = useWeatherStore();
const { current, layer } = storeToRefs(store);

onMounted(async () => {
  if (current.value?.coord) {
    await initMap(current.value.coord.lat, current.value.coord.lon);
    await updateMapLayer(layer.value);
  }
});

watch([current, layer], async () => {
  if (current.value?.coord) {
    await initMap(current.value.coord.lat, current.value.coord.lon);
    await updateMapLayer(layer.value);
  }
});
</script>

<style scoped>
#map {
  height: 100%;
}
</style>
