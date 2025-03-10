<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Config.php';

use App\Models\AddressData;
use App\Services\APIService;
use App\Services\DistanceService;
use App\Services\CSVWriter;

// Process addresses
list($RMTName, $RMTAddress) = explode(' - ', $nameAndAddressRMT);

$RMT = new AddressData($RMTName, $RMTAddress);
$RMTCoordinates = APIService::fetchCoordinates($apiKey, $RMT->getAddress());
$RMT->updateCoordinates($RMTCoordinates['latitude'], $RMTCoordinates['longitude']);

$addresses = [];
foreach ($addressesList as $addressStr) {
    $addressParts = explode(" - ", $addressStr);
    $addressObj = new AddressData($addressParts[0], $addressParts[1]);
    $coords = APIService::fetchCoordinates($apiKey, $addressObj->getAddress());
    $addressObj->updateCoordinates($coords['latitude'], $coords['longitude']);
    $distance = DistanceService::calculateDistance($RMT->getCoordinates()[0], $RMT->getCoordinates()[1], $coords['latitude'], $coords['longitude']);
    $addressObj->updateDistance($distance);
    $addresses[] = $addressObj;
}

// Sort and write to CSV
usort($addresses, fn($a, $b) => $a->getDistance() <=> $b->getDistance());
CSVWriter::writeToCSV($addresses, CSV_FILE_PATH);

echo "CSV file generated at " . CSV_FILE_PATH;
