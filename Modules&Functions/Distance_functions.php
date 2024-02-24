<?php
function calculateDistanceLogic($lat1, $lon1, $lat2, $lon2): string {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $kilometers = $dist * 60 * 1.1515 * 1.609344; // 1.609344 to convert miles to kilometers

    return number_format($kilometers, 2, '.', '');
}
function compareByDistance($a, $b): string {
    return $a->getDistance() - $b->getDistance();
}

function calculateDistances($ADchieveAddress, $addresses): void {
    $ADchieveCoordinates = $ADchieveAddress->getCoordinates();

    foreach ($addresses as $addressToCalculateDistance) {
        $addressCoordinates = $addressToCalculateDistance->getCoordinates();
        if ($addressCoordinates[0] === 0.0 || $addressCoordinates[1] === 0) {
            echo "There was a problem with fetching the address of " . $addressToCalculateDistance->getName() . "\n";
        } else {
            $distanceToAdchieve = calculateDistanceLogic($ADchieveCoordinates[0], $ADchieveCoordinates[1], $addressCoordinates[0], $addressCoordinates[1]);
            $addressToCalculateDistance->updateDistance($distanceToAdchieve);
        }
    }
}