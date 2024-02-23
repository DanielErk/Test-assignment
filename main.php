<?php

class AddressData {
    private $Name;
    private $Address;
    private $latitude;
    private $longitude;
    private $Distance;

    public function __construct($name, $address, $latitude, $longitude) {
        $this->Name = $name;
        $this->Address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->Distance = 0.00;
    }

    public function getName() {
        return $this->Name;
    }
    public function getAddress() {
        return $this->Address;
    }

    public function getCoordinates() {
        return [$this->latitude, $this->longitude];
    }

    public function getDistance() {
        return $this->Distance;
    }
    
    public function updateCoordinates($latitude, $longitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function updateDistance($distance) {
        $this->Distance = $distance;
    }
}

function splitNameAndAddress($address) {
    $tempArr = explode(" - ", $address);
    $keys = array('Name', 'Address');
    $result = array_combine($keys, $tempArr);
    return $result;
}
function connectToPositionStackAPI($apiKey, $query) {
    $url = "http://api.positionstack.com/v1/forward?access_key=$apiKey&query=" . urlencode($query);

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) { 
        return "An error occured while fetching the data";
    }

    return $result;
}

function parseAPIResponse($response) {
    return json_decode($response, true);
}

function getAPIData($apiKey, AddressData $address) {
    $APIDataResult = connectToPositionStackAPI($apiKey, $address->getAddress());
    if ($APIDataResult === "An error occured while fetching the data") {
        return null;
    }
    $parsedResult = parseAPIResponse($APIDataResult);
    $APIDataParams = getAPIDataParams($parsedResult);
    return $APIDataParams;
}

function getAPIDataParams($APIData) {
    $arrayCount = count($APIData["data"]);
    if ($arrayCount === 0) { //If no data was received correctly
        return array( //Set default values to skip this address in the order line
            "latitude"=> 0.0,
            "longitude"=> 0.0,
        );
    }
    $APIDataParams = array( //Update coordinates when correctly received the data
        "latitude" => $APIData["data"][0]["latitude"],
        "longitude" => $APIData["data"][0]["longitude"],
    );
    return $APIDataParams;
}

function updateAddressCoordinates($address, $coordinates) {
    $address->updateCoordinates($coordinates["latitude"], $coordinates["longitude"]);
}

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $kilometers = $dist * 60 * 1.1515 * 1.609344; // 1.609344 to convert miles to kilometers

    return number_format($kilometers, 2, '.', '');
}
function compareByDistance($a, $b) {
    return $a->getDistance() - $b->getDistance();
}

function main() {
    $apiKey = "9eb61b6aa98a57d4201f19b0253c92aa";
    $addresses = [];
    $nameAndAddress = splitNameAndAddress("Adchieve HQ - Sint Janssingel 92, 5211 DA 's-Hertogenbosch, The Netherlands");
    $ADchieveName = $nameAndAddress["Name"];
    $ADchieveAddress = $nameAndAddress["Address"];
    $ADchieveAddress = new AddressData($ADchieveName, $ADchieveAddress, 0.0, 0.0);
    $ADchieveAPICoordinates = getAPIData($apiKey, $ADchieveAddress);
    updateAddressCoordinates($ADchieveAddress, $ADchieveAPICoordinates);   

    $addressData = array(
        "Eastern Enterprise B.V. - Deldenerstraat 70, 7551AH Hengelo, The Netherlands",
        "Eastern Enterprise - 46/1 Office no 1 Ground Floor, Dada House, Inside dada silk mills compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India",
        "Sherlock Holmes - 221B Baker St., London, United Kingdom",
        "Adchieve Rotterdam - Weena 505, 3013 AL Rotterdam, The Netherlands",
        "The White House - 1600 Pennsylvania Avenue, Washington, D.C., USA",
        "The Empire State Building - 350 Fifth Avenue, New York City, NY 10118",
        "The Pope - Saint Martha House, 00120 Citta del Vaticano, Vatican City",
        "Neverland - 5225 Figueroa Mountain Road, Los Olivos, Calif. 93441, USA"
    );


    foreach ($addressData as $addressToSplit) {
        $nameAndAddress = splitNameAndAddress($addressToSplit);
        $Name = $nameAndAddress["Name"];
        $Address = $nameAndAddress["Address"];
        $addressObj = new AddressData($Name, $Address, 0.0, 0.0); // Add new AddressData object to the array
        $addressCoordinates = getAPIData($apiKey, $addressObj);
        updateAddressCoordinates($addressObj, $addressCoordinates);
        $addresses[] = $addressObj;
    }

    foreach( $addresses as $addressToCalculateDistance) {
        $ADchieveCoordinates = $ADchieveAddress->getCoordinates();
        $addressCoordinates = $addressToCalculateDistance->getCoordinates();
        if($addressCoordinates[0] === 0.0 || $addressCoordinates[1] === 0) {
            echo "There was a problem with fetching the address of " . $addressToCalculateDistance->getName() ."\n";
        }
        else {
            $distanceToAdchieve = calculateDistance($ADchieveCoordinates[0], $ADchieveCoordinates[1], $addressCoordinates[0], $addressCoordinates[1]);
            $addressToCalculateDistance->updateDistance($distanceToAdchieve); //Update distance to ADchieve
        }
    }

    usort($addresses, 'compareByDistance');
    $csvFilename = "addresses.csv";
    writeAddressesToCSV($addresses, $csvFilename);
}

function writeAddressesToCSV($addresses, $filename) {
    $file = fopen($filename, 'w');
    $counter = 0;
    if ($file) {
        fputcsv($file, array('Sortnumber', 'Distance', 'Name', 'Address'));
        foreach ($addresses as $address) {
            fputcsv($file, array(
                //Here I need to get the index of address + 1 as sortNumber. Then access the right attributes of the object
                ++$counter,
                $address->getDistance() . " km",
                $address->getName(),
                $address->getAddress(),
            ));
        }
        fclose($file); // Close the file handle after writing all the data
        echo "Addresses written to CSV file successfully.";
    } else {
        echo "Failed to open CSV file for writing.";
    }
}


main();
?>