<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-full flex flex-col">
    <h3 class="text-lg font-semibold mb-2">Předpověď</h3>
    <div class="flex-1" v-if="data">
      <p>Teplota: {{ data.temperature }}°C</p>
      <p>Vlhkost: {{ data.humidity }}%</p>
    </div>
    <div v-else class="flex-1 flex items-center justify-center">Načítání...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const data = ref(null);
async function load() {
  const res = await fetch('/api/dashboard/prediction');
  data.value = await res.json();
}

onMounted(load);
</script>
