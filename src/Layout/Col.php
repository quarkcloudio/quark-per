<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Col
{
    public $span;
    public $row = null;
    public $content = null;

    function __construct($span,$callback = null) {

        if(gettype($callback) == 'object') {
            $callback($this);
        } else {
            $this->content = $callback;
        }

        $this->span = $span;
        
        return $this;
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

            $content = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($content);

            $this->row[] = $element;

            return $element;
        }
    }
}
