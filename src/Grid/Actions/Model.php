<?php

namespace QuarkCMS\QuarkAdmin\Grid\Actions;

use Illuminate\Support\Arr;
use Exception;

class Model
{
    public $methods = [];

    public function __call($method, $arguments)
    {
        $this->methods[][$method] = $arguments;
        return $this;
    }
}
