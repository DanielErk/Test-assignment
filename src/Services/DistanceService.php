<?php
namespace App\Services;

use App\Models\AddressData;

class DistanceService {
    /**
     * This method calculates the distance between two sets of coordinates, and converts the distance from miles to kilometers.
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = rad2deg(acos($dist));
        return round($dist * 60 * 1.1515 * 1.609344, 2);
    }
}
