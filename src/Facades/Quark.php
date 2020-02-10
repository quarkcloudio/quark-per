<?php

namespace Quarkcms\QuarkAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Quark.
 *
 * @see Quark\Quark
 */
class Quark extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quark';
    }
}
