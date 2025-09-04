<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeatherByCity(string $city)
    {
        $apiKey = config('services.openweather.key');
        $url = config('services.openweather.url') . "/weather";

        $response = Http::get($url, [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric', // 攝氏
            'lang' => 'zh_tw'
        ]);

        return $response->json();
    }
}
