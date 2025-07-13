# Ventusky-like Redesign Plan

This document outlines the main tasks required to transform **Weathy** into a modern weather visualisation app inspired by [Ventusky](https://www.ventusky.com/).

## 1. Interactive Map Layers
- Integrate a WebGL map provider such as Mapbox GL or Leaflet with plugins.
- Implement weather overlays (wind, temperature, pressure, precipitation, storms, humidity) using data from OpenWeatherMap or other free APIs.
- Provide a time slider to display historical and forecast data.

## 2. Location Search
- Use OpenWeatherMap geocoding or similar API for searching cities worldwide.
- Implement browser geolocation for quick access to the user's current position.
- Display recent searches and allow bookmarking favourite places.

## 3. Localisation
- Current text strings are stored under `resources/lang`. Extend to additional languages as needed.
- Switch locale on the backend via a route parameter or user preference stored in Pinia.

## 4. UI/UX
- Tailwind CSS already provides responsive design. Use modern fonts and smooth transitions.
- Keep the interface minimalist with a sidebar for search and layer controls, following the style of Ventusky.

## 5. API Layer
- Create dedicated Laravel controllers to proxy and cache external API calls.
- Store cached responses using Laravel cache for improved performance.

## 6. Deployment
- Build assets with `npm run build` and ensure environment variables `OPENWEATHER_KEY` and `APP_URL` are configured.
- Example production setup is provided in the README.

The above steps give an overview of required features and changes. Detailed implementation will involve further refinement of Vue components and backend endpoints.
