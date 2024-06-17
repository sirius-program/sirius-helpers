<?php

namespace SiriusProgram\SiriusHelpers;

class Sirius
{
    public function __construct(private mixed $string = '')
    {
        // 
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function of(mixed $string): static
    {
        $this->string = $string;

        return $this;
    }

    public function dateTime(): DateTimeHelpers
    {
        return new DateTimeHelpers($this->string);
    }

    public function number(): NumberHelpers
    {
        return new NumberHelpers($this->string);
    }

    public function string(): StringHelpers
    {
        return new StringHelpers($this->string);
    }

    // Other Helper

    public static function calculateDistance(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, float $earthRadius = 6371000): float
    {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
