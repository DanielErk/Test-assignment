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


function updateAddressCoordinates($address, $coordinates) {
    $address->updateCoordinates($coordinates["latitude"], $coordinates["longitude"]);
}

function createAddressData($name, $address, $latitude, $longitude) {
    return new AddressData($name, $address, $latitude, $longitude);
}