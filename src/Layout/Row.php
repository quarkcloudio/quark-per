<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Row
{
    public $content = null;
    public $col = null;

    function __construct($callback = null) {
        if(gettype($callback) == 'object') {
            $callback($this);
        } else {
            $this->content = $callback;
        }
        
        return $this;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'col' => Col::class,
    ];

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        $class = Arr::get(static::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {
            $span = Arr::get($arguments, 0, ''); //[0];
            $argument = Arr::get($arguments, 1, ''); //[1];
            $element = new $className($span, $argument);

            $this->col[] = $element;
            return $element;
        }
    }
}
