<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-full flex flex-col">
    <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">Graf vývoje</h3>
    <canvas ref="canvas" class="flex-1" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Chart, LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler } from 'chart.js';

Chart.register(LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler);

const canvas = ref(null);

async function load() {
  const res = await fetch('/api/dashboard/chart?type=temperature&period=30');
  const data = await res.json();
  const labels = data.map(d => d.date);
  const values = data.map(d => d.value);
  new Chart(canvas.value.getContext('2d'), {
    type: 'line',
    data: {
      labels,
      datasets: [{
        data: values,
        fill: true,
        borderColor: '#6366f1',
        backgroundColor: 'rgba(99,102,241,0.2)',
        tension: 0.4,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
  });
}

onMounted(load);
</script>
