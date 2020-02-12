<?php

namespace QuarkCMS\QuarkAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Quark.
 *
 * @see Quark\Quark
 */
class QuarkAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quark-admin';
    }
}
