<?php
require_once 'Global_Variables/Config.php';

function writeAddressesToCSV($addresses, $filename): void {
    $file = fopen($filename, 'w');
    $counter = 0;
    if ($file) {
        // Write header row
        fputcsv($file, getConfigHeaders());

        foreach ($addresses as $address) {
            fputcsv($file, getConfigValues($address, ++$counter));
        }

        fclose($file); // Close the file handle after writing all the data
        echo "Addresses written to CSV file successfully.";
    } else {
        echo "Failed to open CSV file for writing.";
    }
}

function getConfigHeaders(): array {
    return array(
        str_pad('Sortnumber', SORT_NUMBER_WIDTH),
        str_pad('Distance', DISTANCE_WIDTH),
        str_pad('Name', NAME_WIDTH),
        str_pad('Address', ADDRESS_WIDTH)
    );
}

function getConfigValues($address, $counter): array {
    return array(
        str_pad($counter, SORT_NUMBER_WIDTH),
        str_pad($address->getDistance() . " km", DISTANCE_WIDTH),
        str_pad($address->getName(), NAME_WIDTH),
        str_pad($address->getAddress(), ADDRESS_WIDTH)
    );
}