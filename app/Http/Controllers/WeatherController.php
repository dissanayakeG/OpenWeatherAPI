<?php

namespace App\Http\Controllers;

use App\Weather;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log as FacadesLog;

/**
 * Class WeatherController
 * @package App\Http\Controllers
 */
class WeatherController extends Controller
{
    private $factory;
    private $database;

    public function __construct()
    {
        $this->factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/weathergather-2c34e-firebase-adminsdk-iyvfz-45d02f28ef.json')
            ->withDatabaseUri('https://weathergather-2c34e-default-rtdb.firebaseio.com/');

        $this->database = $this->factory->createDatabase();
    }

    private function _getCurrentWeatherDetails()
    {
        $cityName = 'colombo';
        $url = 'api.openweathermap.org/data/2.5/weather?q=' . $cityName . '&appid=' . env('API_KEY') . '';
        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        return json_decode($result->getBody()->getContents(), true);
    }

    private function _kelvinToCelsius($temp)
    {
        if (!is_numeric($temp)) {
            return false;
        }
        return round(($temp - 273.15));
    }

    private function _calculateOneHourAverageTemperature()
    {
        return Weather::avg('temperature');
    }

    private function _calculateOneHourAverageHumidity()
    {
        return Weather::avg('humidity');
    }

    /**
     * Update Persistence database
     */

    public function updateWeatherDetailsInSQL()
    {
        FacadesLog::info('sql');
        $currentWeatherDetails = $this->_getCurrentWeatherDetails();
        $saveData = [
            'description' => $currentWeatherDetails['weather'][0]['description'],
            'temperature' => $this->_kelvinToCelsius($currentWeatherDetails['main']['temp']),
            'humidity' => $currentWeatherDetails['main']['humidity'],
            'api_response' => json_encode($currentWeatherDetails)
        ];
        if (count(Weather::all()) >= 60) {
            Weather::first()->delete();
        }
        $weather = Weather::create($saveData);
        return response($weather, 200);
    }

    /**
     * Update firebase
     */

    public function updateWeatherDetailsInFireBase()
    {
        FacadesLog::info('firebase');
        $currentWeatherDetails = $this->_getCurrentWeatherDetails();
        $saveData = $this->database
            ->getReference('open_weather/weather_details')
            ->push([
                'description' => $currentWeatherDetails['weather'][0]['description'],
                'temperature' => $this->_kelvinToCelsius($currentWeatherDetails['main']['temp']),
                'humidity' => $currentWeatherDetails['main']['humidity'],
                'one_hour_ave_temperature' => $this->_calculateOneHourAverageTemperature(),
                'one_hour_ave_humidity' => $this->_calculateOneHourAverageHumidity(),
            ]);

        return response($saveData, 200);
    }

    /**
     * Return Current weather Details by request parameter
     * parameter can be temperature, humidity and all
     */

    public function getCurrentWeather(Request $request)
    {
        $filterQuery = $request->route()->param;
        $currentWeatherDetails = null;
        if ($filterQuery != '') {
            $currentWeatherDetails = $this->_getCurrentWeatherDetails();
            if ($filterQuery == 'all') {
                $currentWeatherDetails = $currentWeatherDetails['main'];
            } else {
                $currentWeatherDetails = $currentWeatherDetails['main'][$filterQuery];
            }
        }
        return response([$filterQuery => $currentWeatherDetails], 200);
    }

    /**
     * Return Realtime Details from firebase
     */
    public function getRealTimeWeather()
    {
        $reference = $this->database->getReference('open_weather/weather_details');
        $snapshot = $reference->getSnapshot()->getValue();
        return response(end($snapshot), 200);
    }
}
