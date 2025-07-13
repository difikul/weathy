import { defineStore } from 'pinia';
import { fetchWeather, fetchForecast } from '../weather';

export const useWeatherStore = defineStore('weather', {
  state: () => ({
    current: null,
    forecast: [],
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
  },
});
