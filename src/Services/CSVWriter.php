<?php
namespace App\Services;

use App\Models\AddressData;

class CSVWriter {
    /**
     * This method writes the address data to a CSV file.
     * @param AddressData[] $addresses
     * @param string $filename
     */
    public static function writeToCSV(array $addresses, string $filename): void {
        $file = fopen($filename, 'w');
        fputcsv($file, ['SortNumber', 'Distance (km)', 'Name', 'Address']);

        foreach ($addresses as $index => $address) {
            fputcsv($file, [$index + 1, $address->getDistance(), $address->getName(), $address->getAddress()]);
        }

        fclose($file);
    }
}
