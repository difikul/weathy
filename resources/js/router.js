import { createRouter, createWebHistory } from 'vue-router';
import WeatherApp from './components/WeatherApp.vue';
import DashboardPage from './components/DashboardPage.vue';

const routes = [
  { path: '/', component: WeatherApp },
  { path: '/dashboard', component: DashboardPage }
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
