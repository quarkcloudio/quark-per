<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;

/**
 * Class Quark.
 */
class Quark
{
    /**
     * Get the current Quark version.
     *
     * @return string
     */
    public static function version()
    {
        return '1.0.0';
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Form
     */
    public function form($model = null)
    {
        return new Form($model);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Layout\Content
     */
    public function table($model = null)
    {
        return new Table($model);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Layout\Show
     */
    public function show($model = null)
    {
        return new Show($model);
    }
}
