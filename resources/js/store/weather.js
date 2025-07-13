import { defineStore } from 'pinia';
import { fetchWeather, fetchForecast, fetchCombined, fetchStats } from '../weather';

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    forecast: [],
    combined: {},
    stats: null,
    layer: 'precipitation_new',
  }),
  actions: {
    async loadByCity(city) {
      this.current = await fetchWeather({ city });
      if (this.current.coord) {
        this.forecast = await fetchForecast({
          lat: this.current.coord.lat,
          lon: this.current.coord.lon,
        });
        this.stats = await fetchStats({});
      }
    },
    async loadByCoords(lat, lon) {
      this.current = await fetchWeather({ lat, lon });
      if (this.current.coord) {
        this.forecast = await fetchForecast({ lat, lon });
        this.stats = await fetchStats({});
      }
    },
    async loadCombined(lat, lon) {
      this.combined = await fetchCombined({ lat, lon });
    },
    setLayer(l) {
      this.layer = l;
    },
    async loadStats(period = 7) {
      this.stats = await fetchStats({ period });
    },
  },
});
