<?php

require_once 'Modules&Functions/API_functions.php';
require_once 'Modules&Functions/Address_module.php';
require_once 'Modules&Functions/Distance_functions.php';
require_once 'Modules&Functions/Write_to_CSV_function.php';
require_once 'Global_Variables/Config.php';

function main() {
    global $apiKey, $addressData, $nameAndAddressADchieve, $csvFilename;

    $ADchieveAddress = processADchieveAddress($apiKey, $nameAndAddressADchieve);
    $addresses = processAddresses($apiKey, $addressData);

    calculateDistances($ADchieveAddress, $addresses);

    usort($addresses, 'compareByDistance');
    writeAddressesToCSV($addresses, $csvFilename);
}

function processADchieveAddress($apiKey, $nameAndAddressADchieve) {
    $nameAndAddressADchieveSplit = splitNameAndAddress($nameAndAddressADchieve);
    $ADchieveName = $nameAndAddressADchieveSplit["Name"];
    $ADchieveAddress = $nameAndAddressADchieveSplit["Address"];

    $ADchieveAddress = createAddressData($ADchieveName, $ADchieveAddress, 0.0, 0.0);
    $ADchieveAPICoordinates = getAPIData($apiKey, $ADchieveAddress);
    updateAddressCoordinates($ADchieveAddress, $ADchieveAPICoordinates);

    return $ADchieveAddress;
}

function processAddresses($apiKey, $addressData) {
    $addresses = [];

    foreach ($addressData as $addressToSplit) {
        $nameAndAddress = splitNameAndAddress($addressToSplit);
        $Name = $nameAndAddress["Name"];
        $Address = $nameAndAddress["Address"];
        $addressObj = createAddressData($Name, $Address, 0.0, 0.0);
        $addressCoordinates = getAPIData($apiKey, $addressObj);
        updateAddressCoordinates($addressObj, $addressCoordinates);
        $addresses[] = $addressObj;
    }

    return $addresses;
}


main();
