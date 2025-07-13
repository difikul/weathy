# Weathy

Weathy is a Laravel 10 and Vue 3 application for exploring weather data. The frontend is powered by Vite, Tailwind CSS and Vue with Pinia state management. Weather information is retrieved from the OpenWeatherMap API.

## Local development

```bash
# install PHP dependencies
composer install

# install frontend dependencies
npm install

# run the development servers
npm run dev &
php artisan serve
```

Open <http://localhost:8000> in your browser.

## Building for production

```bash
npm run build
```

The compiled assets will be stored in `public/build` and served automatically when `APP_ENV=production`.

## Testing

Run the automated test suite with:

```bash
php artisan test
```

## Continuous Integration

GitHub Actions configuration is provided in `.github/workflows/ci.yml` to install dependencies, run tests and build the production assets.
