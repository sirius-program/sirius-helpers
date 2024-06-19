<?php

if (!function_exists('sirius')) {
    function sirius(mixed $string = ''): SiriusProgram\SiriusHelpers\Sirius
    {
        return new Sirius($string);
    }
}

if (!function_exists('sstring')) {
    function sstring(string $string = ''): SiriusProgram\SiriusHelpers\StringHelpers
    {
        return sirius($string)->string();
    }
}

if (!function_exists('snumber')) {
    function snumber(string|int|float $string = ''): SiriusProgram\SiriusHelpers\NumberHelpers
    {
        return sirius($string)->number();
    }
}

if (!function_exists('sdatetime')) {
    function sdatetime(string|int|\DateTime|Carbon\Carbon $string = ''): SiriusProgram\SiriusHelpers\DateTimeHelpers
    {
        return sirius($string)->dateTime();
    }
}
