<?php


namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Str;

class Helper
{
    public static function __callStatic($name, $arguments)
    {
        return Str::snake($name)(...$arguments);
    }
}
