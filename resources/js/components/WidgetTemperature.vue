<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-full flex flex-col">
    <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
      <span>Teplotní statistiky</span>
    </h3>
    <div class="flex-1 space-y-2" v-if="data">
      <p>Týdenní průměr: <strong>{{ data.week.temperature }}°C</strong></p>
      <p>Měsíční průměr: <strong>{{ data.month.temperature }}°C</strong></p>
    </div>
    <div v-else class="flex-1 flex items-center justify-center">Načítání...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const data = ref(null);

async function load() {
  const res = await fetch('/api/dashboard/summary');
  data.value = await res.json();
}

onMounted(load);
</script>
