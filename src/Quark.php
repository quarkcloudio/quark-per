<?php

namespace Tangtanglove\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Class Quark.
 */
class Quark
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
