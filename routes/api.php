<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('weather/{param?}', 'WeatherController@getCurrentWeather');
Route::get('/realtime-weather', 'WeatherController@getRealTimeWeather');
Route::get('/update-weather-details-in-sql', 'WeatherController@updateWeatherDetailsInSQL');
Route::get('/update-weather-details-in-firebase', 'WeatherController@updateWeatherDetailsInFirebase');
