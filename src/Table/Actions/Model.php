<?php

namespace QuarkCMS\QuarkAdmin\Table\Actions;

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
