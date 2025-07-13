<template>
  <div v-if="stats" class="space-y-1">
    <h2 class="font-semibold text-lg">{{ trans.stats_title }}</h2>
    <p>{{ trans.stats_average }}: {{ stats.avg_temp }}°C</p>
    <p>{{ trans.stats_min }}: {{ stats.min_temp }}°C</p>
    <p>{{ trans.stats_max }}: {{ stats.max_temp }}°C</p>
    <p>{{ trans.stats_predicted }}: {{ stats.predicted_temp }}°C</p>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useWeatherStore } from '../store/weather';

const store = useWeatherStore();
const { stats } = storeToRefs(store);
const trans = window.trans;
</script>
