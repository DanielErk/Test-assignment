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

    public function getName(): string {
        return $this->Name;
    }
    public function getAddress(): string {
        return $this->Address;
    }

    public function getCoordinates(): array {
        return [$this->latitude, $this->longitude];
    }

    public function getDistance(): string {
        return $this->Distance;
    }
    
    public function updateCoordinates($latitude, $longitude): void {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function updateDistance($distance): void {
        $this->Distance = $distance;
    }
}
function splitNameAndAddress($address): array {
    $tempArr = explode(" - ", $address);
    $keys = array('Name', 'Address');
    $result = array_combine($keys, $tempArr);
    return $result;
}


function updateAddressCoordinates($address, $coordinates): void {
    $address->updateCoordinates($coordinates["latitude"], $coordinates["longitude"]);
}

function createAddressData($name, $address, $latitude, $longitude): AddressData {
    return new AddressData($name, $address, $latitude, $longitude);
}