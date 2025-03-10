<?php
require_once __DIR__ . '/addresses.php';

define('CSV_FILE_PATH', __DIR__ . '/../../storage/addresses.csv');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$apiKey = $_ENV['API_KEY'];

$addressesList = require __DIR__ . '/addresses.php';

$nameAndAddressRMT = "RTM Business Rotterdam - Van Nelleweg 1, 3044BC Rotterdam, The Netherlands";


if (!is_dir(__DIR__ . '/../../storage')) {
    mkdir(__DIR__ . '/../../storage', 0777, true);
}

if (!file_exists(CSV_FILE_PATH)) {
    $file = fopen(CSV_FILE_PATH, 'w');
    if ($file !== false) {
        fputcsv($file, ["Sortnumber", "Distance", "Name", "Address"]);
        fclose($file);
    } else {
        echo "Failed to create the CSV file.";
        exit;
    }
}