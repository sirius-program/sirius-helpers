<?php

declare(strict_types=1);

namespace SiriusProgram\SiriusHelpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Sirius implements \Stringable
{
    public function __construct(private mixed $data = '')
    {
        //
    }

    public function __toString(): string
    {
        return (string) $this->data;
    }

    public function of(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function array(?array $array = null): ArrayHelpers
    {
        return new ArrayHelpers($array ?? $this->data);
    }

    public function dateTime(string|int|\DateTime|Carbon|null $datetime = null): DateTimeHelpers
    {
        return new DateTimeHelpers($datetime ?? $this->data);
    }

    public function number(int|float|null $number = null): NumberHelpers
    {
        return new NumberHelpers($number ?? (float) $this->data);
    }

    public function string(?string $data = null): StringHelpers
    {
        return new StringHelpers($data ?? $this->data);
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

        $angle = 2 * asin(sqrt(sin($latitudeDelta / 2) ** 2 + cos($latitudeFrom) * cos($latitudeTo) * sin($longitudeDelta / 2) ** 2));

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
        $country = Cache::rememberForever('country-detail-' . $countryCode, function () use ($countryCode) {
            $response = Http::get('https://restcountries.com/v3.1/alpha/' . $countryCode);

            if ($response->failed()) {
                throw new \Exception('failed to get the country detail');
            }

            return json_decode($response->body())[0];
        });

        return [
            'code' => $country->cca2,
            'name' => match (config('app.locale')) {
                default => $country->name->common,
                'ja'    => $country->translations?->jpn?->common ?? $country->name->common,
                'id'    => $country->translations?->ind?->common ?? $country->name->common,
            },
            'dailingCode' => collect($country->idd)->flatten()->implode(''),
        ];
    }
}
