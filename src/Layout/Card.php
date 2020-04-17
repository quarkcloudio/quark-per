<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Card
{
    public $component = null;

    function __construct($title,$callback = null) {
        $this->component['title'] = $title;
        $callback($this);

        return $this;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'text'=> Text::class,
        'statistic'=> Statistic::class,
        'table'=> Table::class
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

            if($method == 'text') {
                $content = Arr::get($arguments, 0, ''); //[0];
                $element = new $className($content);
            }

            if($method == 'statistic') {
                $title = Arr::get($arguments, 0, ''); //[0];
                $argument = Arr::get($arguments, 1, ''); //[1];
                $element = new $className($title, $argument);
            }

            if($method == 'table') {
                $content = Arr::get($arguments, 0, ''); //[0];
                $element = new $className($content);
            }

            $this->component['name'] = 'card';
            $this->component['items'][]= $element;
            return $element;
        }
    }
}
