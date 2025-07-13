import { defineStore } from 'pinia';
import { fetchWeather, fetchForecast, fetchCombined, fetchStats, fetchPrediction } from '../weather';

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    forecast: [],
    combined: {},
    stats: {},
    prediction: {},
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
      }
      await this.loadStats();
      await this.loadPrediction();
    },
    async loadByCoords(lat, lon) {
      this.current = await fetchWeather({ lat, lon });
      if (this.current.coord) {
        this.forecast = await fetchForecast({ lat, lon });
      }
      await this.loadStats();
      await this.loadPrediction();
    },
    async loadCombined(lat, lon) {
      this.combined = await fetchCombined({ lat, lon });
    },
    async loadStats() {
      this.stats = await fetchStats();
    },
    async loadPrediction() {
      this.prediction = await fetchPrediction();
    },
    setLayer(l) {
      this.layer = l;
    },
  },
});
