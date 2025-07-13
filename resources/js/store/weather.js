import { defineStore } from 'pinia';
import { fetchWeather, fetchForecast, fetchCombined } from '../weather';

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    forecast: [],
    combined: {},
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
    },
    async loadByCoords(lat, lon) {
      this.current = await fetchWeather({ lat, lon });
      if (this.current.coord) {
        this.forecast = await fetchForecast({ lat, lon });
      }
    },
    async loadCombined(lat, lon) {
      this.combined = await fetchCombined({ lat, lon });
    },
    setLayer(l) {
      this.layer = l;
    },
  },
});
