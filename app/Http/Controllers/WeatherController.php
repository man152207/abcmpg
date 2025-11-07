<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function show(Request $request)
{
    $lat  = $request->query('lat');
    $lon  = $request->query('lon');
    $city = $request->query('city', env('WEATHER_DEFAULT_CITY', 'Pokhara,NP'));

    $cacheKey = $lat && $lon ? "wx:{$lat},{$lon}" : "wx:{$city}";

    // पहिले cache मा छ कि छैन हेर्ने
    $data = Cache::get($cacheKey);

    if (!$data) {
        // cache मा छैन भने, API call गर
        $apiKey = env('OPENWEATHER_API_KEY');
        if ($apiKey) {
            $endpoint = 'https://api.openweathermap.org/data/2.5/weather';
            $params = $lat && $lon
                ? ['lat' => $lat, 'lon' => $lon, 'appid' => $apiKey, 'units' => 'metric']
                : ['q' => $city, 'appid' => $apiKey, 'units' => 'metric'];

            $res = Http::timeout(8)->get($endpoint, $params);

            if ($res->ok()) {
                $j = $res->json();
                $cond = strtolower($j['weather'][0]['main'] ?? 'clear');

                $data = [
                    'city'        => $j['name'] ?? $city,
                    'temp'        => isset($j['main']['temp']) ? (int) round($j['main']['temp']) : null,
                    'description' => $j['weather'][0]['description'] ?? 'clear sky',
                    'condition'   => $cond,
                    'theme'       => $this->mapTheme($cond),
                ];

                // IMPORTANT: सफल डाटा मात्र cache मा हाल
                Cache::put($cacheKey, $data, now()->addMinutes(10));
            }
        }
    }

    // अझै पनि data नभएको अवस्था (API fail भयो, cache पनि खाली)
    if (!$data) {
        $data = [
            'city'        => $city,
            'temp'        => null,
            'description' => 'unavailable',
            'condition'   => 'clear',
            'theme'       => 'clear',
        ];
    }

    return response()->json($data);
}

    private function mapTheme(string $cond): string
    {
        if (str_contains($cond,'thunder')) return 'thunder';
        if (str_contains($cond,'drizzle')) return 'rain';
        if (str_contains($cond,'rain'))    return 'rain';
        if (str_contains($cond,'snow'))    return 'snow';
        if (str_contains($cond,'cloud'))   return 'clouds';
        if (str_contains($cond,'mist') || str_contains($cond,'fog') || str_contains($cond,'haze')) return 'mist';
        return 'clear';
    }
}
