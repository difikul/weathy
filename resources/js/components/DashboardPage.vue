<template>
  <div class="p-4">
    <GridLayout
      :layout="layout"
      :col-num="12"
      :row-height="30"
      :is-draggable="true"
      :is-resizable="true"
      @layout-updated="saveLayout"
      class="layout">
      <GridItem v-for="item in layout" :key="item.i" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i">
        <component :is="getWidget(item.i)" class="h-full" />
      </GridItem>
    </GridLayout>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { GridLayout, GridItem } from 'vue3-grid-layout';
import WidgetTemperature from './WidgetTemperature.vue';
import WidgetGraph from './WidgetGraph.vue';
import WidgetTable from './WidgetTable.vue';
import WidgetPrediction from './WidgetPrediction.vue';

const defaultLayout = [
  { i: 'temp', x: 0, y: 0, w: 4, h: 3 },
  { i: 'graph', x: 4, y: 0, w: 8, h: 4 },
  { i: 'table', x: 0, y: 3, w: 4, h: 4 },
  { i: 'predict', x: 4, y: 4, w: 8, h: 3 },
];

const layout = ref([]);

function getWidget(name) {
  switch (name) {
    case 'temp':
      return WidgetTemperature;
    case 'graph':
      return WidgetGraph;
    case 'table':
      return WidgetTable;
    case 'predict':
      return WidgetPrediction;
  }
}

function loadLayout() {
  const saved = localStorage.getItem('dashboardLayout');
  layout.value = saved ? JSON.parse(saved) : defaultLayout;
}

function saveLayout(newLayout) {
  localStorage.setItem('dashboardLayout', JSON.stringify(newLayout));
}

onMounted(loadLayout);
</script>

<style scoped>
.layout {
  min-height: 80vh;
}
</style>
