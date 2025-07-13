import { createRouter, createWebHistory } from 'vue-router';
import WeatherApp from './components/WeatherApp.vue';

const routes = [
  { path: '/', component: WeatherApp }
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
