<?php

if (!function_exists('sirius')) {
    function sirius(mixed $string = ''): SiriusProgram\SiriusHelpers\Sirius
    {
        return new SiriusProgram\SiriusHelpers\Sirius($string);
    }
}

if (!function_exists('sString')) {
    function sString(string $string = ''): SiriusProgram\SiriusHelpers\StringHelpers
    {
        return sirius($string)->string();
    }
}

if (!function_exists('sNumber')) {
    function sNumber(string|int|float $string = ''): SiriusProgram\SiriusHelpers\NumberHelpers
    {
        return sirius($string)->number();
    }
}

if (!function_exists('sDateTime')) {
    function sDateTime(string|int|\DateTime|Carbon\Carbon $string = ''): SiriusProgram\SiriusHelpers\DateTimeHelpers
    {
        return sirius($string)->dateTime();
    }
}
