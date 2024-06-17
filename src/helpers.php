<?php

if (!function_exists('sirius')) {
    function sirius(string $string = ''): Sirius
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
    function snumber(string $string = ''): NumberHelpers
    {
        return sirius($string)->number();
    }
}

if (!function_exists('sdatetime')) {
    function sdatetime(string $string = ''): DateTimeHelpers
    {
        return sirius($string)->dateTime();
    }
}