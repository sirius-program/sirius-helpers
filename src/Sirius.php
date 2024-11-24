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

    public function dateTime(string|int|\DateTime|Carbon\Carbon|null $datetime = null): DateTimeHelpers
    {
        return new DateTimeHelpers($datetime ?? $this->string);
    }

    public function number(int|float|null $number = null): NumberHelpers
    {
        return new NumberHelpers($number ?? (float) $this->string);
    }

    public function string(?string $string = null): StringHelpers
    {
        return new StringHelpers($string ?? $this->string);
    }

    // Other Helper

    public static function calculateDistanceInMeters(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, float $earthRadius = 6371000): float
    {
        $latitudeFrom = deg2rad($latitudeFrom);
        $longitudeFrom = deg2rad($longitudeFrom);
        $latitudeTo = deg2rad($latitudeTo);
        $longitudeTo = deg2rad($longitudeTo);

        $latitudeDelta = $latitudeTo - $latitudeFrom;
        $longitudeDelta = $longitudeTo - $longitudeFrom;

        $angle = 2 * asin(sqrt(pow(sin($latitudeDelta / 2), 2) + cos($latitudeFrom) * cos($latitudeTo) * pow(sin($longitudeDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public static function setNullIfBlank(mixed $data, bool $keepZero = false, bool $keepEmptyArray = false, bool $keepEmptyString = false): mixed
    {
        if ($keepZero && $data === 0) {
            return $data;
        }

        if ($keepEmptyArray && $data === []) {
            return $data;
        }

        if ($keepEmptyString && $data === '') {
            return $data;
        }

        if (!is_array($data) || $data === []) {
            $data = (!empty($data) || $data === false) ? $data : null;
        } else {
            foreach ($data as $key => $value) {
                $data[$key] = self::setNullIfBlank($value, $keepZero, $keepEmptyArray, $keepEmptyString);
            }
        }

        return $data;
    }

    public static function getCountryDetail(string $countryCode): array
    {
        $country = \Illuminate\Support\Facades\Cache::rememberForever("country-detail-$countryCode", function () use ($countryCode) {
            $response = \Illuminate\Support\Facades\Http::get("https://restcountries.com/v3.1/alpha/$countryCode");

            if ($response->failed()) {
                throw new \Exception('failed to get the country detail');
            }

            return json_decode($response->body())[0];
        });

        return [
            'dailingCode' => collect($country->idd)->flatten()->implode(''),
        ];
    }
}
