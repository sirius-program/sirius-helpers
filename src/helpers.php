<?php

if (!function_exists('sirius')) {
    function sirius(mixed $string = ''): Sirius
    {
        return new Sirius($string);
    }
}

if (!function_exists('sstring')) {
    function sstring(string $string = ''): StringHelpers
    {
        return sirius($string)->string();
    }
}

if (!function_exists('snumber')) {
    function snumber(string|int|float $string = ''): NumberHelpers
    {
        return sirius($string)->number();
    }
}

if (!function_exists('sdatetime')) {
    function sdatetime(string|int|\DateTime|Carbon\Carbon $string = ''): DateTimeHelpers
    {
        return sirius($string)->dateTime();
    }
}
