<?php

declare(strict_types=1);

use Carbon\Carbon;
use SiriusProgram\SiriusHelpers\ArrayHelpers;
use SiriusProgram\SiriusHelpers\DateTimeHelpers;
use SiriusProgram\SiriusHelpers\NumberHelpers;
use SiriusProgram\SiriusHelpers\Sirius;
use SiriusProgram\SiriusHelpers\StringHelpers;

if (!function_exists('sirius')) {
    function sirius(mixed $data = ''): Sirius
    {
        return new Sirius($data);
    }
}

if (!function_exists('sString')) {
    function sString(string $string = ''): StringHelpers
    {
        return sirius($string)->string();
    }
}

if (!function_exists('sNumber')) {
    function sNumber(string|int|float $string = ''): NumberHelpers
    {
        return sirius($string)->number();
    }
}

if (!function_exists('sDateTime')) {
    function sDateTime(string|int|DateTime|Carbon $string = ''): DateTimeHelpers
    {
        return sirius($string)->dateTime();
    }
}

if (!function_exists('sArray')) {
    function sArray(array $array = []): ArrayHelpers
    {
        return sirius($array)->array();
    }
}
