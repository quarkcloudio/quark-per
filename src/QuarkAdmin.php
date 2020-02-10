<?php

namespace Quarkcms\QuarkAdmin;

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
     * @return \Quark\Form
     */
    public function form()
    {
        return new Form();
    }
}
