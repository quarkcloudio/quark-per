<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class Quark.
 */
class QuarkAdmin
{
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
    public function Content()
    {
        return new Layout\Content();
    }
}
