<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WeatherController;

Route::get('/', [WeatherController::class, 'index']);
Route::get('/api/weather', [WeatherController::class, 'getWeather']);
Route::get('/api/forecast', [WeatherController::class, 'getForecast']);
Route::get('/api/combined', [WeatherController::class, 'combined']);
Route::get('/api/stats', [WeatherController::class, 'stats']);
Route::get('/api/predict', [WeatherController::class, 'predict']);
