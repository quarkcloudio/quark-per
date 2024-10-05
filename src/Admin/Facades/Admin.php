<?php

namespace QuarkCloudIO\QuarkAdmin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Admin.
 *
 * @see Quark\Quark
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'admin';
    }
}
