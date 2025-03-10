<?php
namespace App\Models;

class AddressData {
    private string $name;
    private string $address;
    private float $latitude;
    private float $longitude;
    private float $distance = 0.00;

    public function __construct(string $name, string $address, float $latitude = 0.0, float $longitude = 0.0) {
        $this->name = $name;
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function getCoordinates(): array {
        return [$this->latitude, $this->longitude];
    }

    public function getDistance(): float {
        return $this->distance;
    }

    public function updateCoordinates(float $latitude, float $longitude): void {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function updateDistance(float $distance): void {
        $this->distance = $distance;
    }
}
