<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-full flex flex-col">
    <h3 class="text-lg font-semibold mb-2">Tabulka extrémů</h3>
    <table class="min-w-full text-sm" v-if="data">
      <tbody class="divide-y divide-gray-200">
        <tr>
          <td class="py-1">Max. teplota</td>
          <td class="py-1 text-right">{{ data.max_temperature.value }}°C</td>
        </tr>
        <tr>
          <td class="py-1">Min. teplota</td>
          <td class="py-1 text-right">{{ data.min_temperature.value }}°C</td>
        </tr>
        <tr>
          <td class="py-1">Max. vlhkost</td>
          <td class="py-1 text-right">{{ data.max_humidity.value }}%</td>
        </tr>
        <tr>
          <td class="py-1">Min. vlhkost</td>
          <td class="py-1 text-right">{{ data.min_humidity.value }}%</td>
        </tr>
      </tbody>
    </table>
    <div v-else class="flex-1 flex items-center justify-center">Načítání...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const data = ref(null);

async function load() {
  const res = await fetch('/api/dashboard/extremes');
  data.value = await res.json();
}

onMounted(load);
</script>
