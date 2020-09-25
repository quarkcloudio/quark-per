<?php

namespace QuarkCMS\QuarkAdmin\Actions;

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

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->methods;
    }
}
