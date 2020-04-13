<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Row
{
    public $component = null;

    function __construct($callback = null) {

        $callback($this);
        
        return $this;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'col' => Col::class,
        'text'=> Text::class
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

            $this->component['name'] = 'row';
            $this->component['items'][]= $element;
            return $element;
        }
    }
}
