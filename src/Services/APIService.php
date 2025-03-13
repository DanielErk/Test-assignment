<?php
namespace App\Services;

class APIService {
    /**
     * This method fetches the coordinates of a location using the Positionstack API.
     * @param string $apiKey
     * @param string $query
     * @return array|null
     */
    public static function fetchCoordinates(string $apiKey, string $query): ?array {
        $url = "http://api.positionstack.com/v1/forward?access_key={$apiKey}&query=" . urlencode($query);
        $response = file_get_contents($url);

        if (!$response) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['data'][0] ?? ['latitude' => 0.0, 'longitude' => 0.0];
    }
}
