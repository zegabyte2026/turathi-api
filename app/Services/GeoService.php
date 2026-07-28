<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoService
{
    private const API_URL = 'http://ip-api.com/json/{ip}?fields=status,country,regionName,city,lat,lon';

    public static function resolve(string $ip): array
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return [
                'latitude'  => null,
                'longitude' => null,
                'city'      => null,
                'region'    => null,
                'country'   => null,
            ];
        }

        try {
            $url = str_replace('{ip}', $ip, self::API_URL);
            $response = Http::timeout(3)->get($url);

            if ($response->successful() && data_get($response->json(), 'status') === 'success') {
                $data = $response->json();
                return [
                    'latitude'  => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'city'      => $data['city'] ?? null,
                    'region'    => $data['regionName'] ?? null,
                    'country'   => $data['country'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('GeoService: failed to resolve IP ' . $ip, ['error' => $e->getMessage()]);
        }

        return [
            'latitude'  => null,
            'longitude' => null,
            'city'      => null,
            'region'    => null,
            'country'   => null,
        ];
    }
}
