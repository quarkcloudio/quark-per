<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Col
{
    public $span;
    public $content;

    function __construct($span,$callback = null) {

        if ($callback instanceof Closure) {
            $callback($this->row);
        } else {
            $content = Arr::get($callback, 0, '');
            $this->content = $content;
        }

        $this->span = $span;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'row' => Row::class,
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

            $column = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($column, array_slice($arguments, 1));

            $this->content['row'][] = $element;

            return $element;
        }
    }
}
