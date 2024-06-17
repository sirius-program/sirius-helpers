<?php

namespace SiriusProgram\SiriusHelpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SiriusProgram\SiriusHelpers\Sirius
 */
class SiriusHelpers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SiriusProgram\SiriusHelpers\Sirius::class;
    }
}
