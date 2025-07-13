<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\DashboardController;

Route::get('/', [WeatherController::class, 'index']);
Route::get('/dashboard', [WeatherController::class, 'index']);
Route::get('/api/weather', [WeatherController::class, 'getWeather']);
Route::get('/api/forecast', [WeatherController::class, 'getForecast']);
Route::get('/api/combined', [WeatherController::class, 'combined']);
Route::get('/api/stats', [WeatherController::class, 'stats']);
Route::get('/api/predict', [WeatherController::class, 'predict']);

Route::prefix('api/dashboard')->group(function () {
    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/extremes', [DashboardController::class, 'extremes']);
    Route::get('/prediction', [DashboardController::class, 'prediction']);
    Route::get('/chart', [DashboardController::class, 'chart']);
});
