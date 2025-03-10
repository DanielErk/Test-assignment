<?php
namespace App\Services;

use App\Models\AddressData;

class DistanceService {
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = rad2deg(acos($dist));
        return round($dist * 60 * 1.1515 * 1.609344, 2); // Convert miles to km
    }
}
