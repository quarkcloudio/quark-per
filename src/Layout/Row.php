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

    public function gutter($gutter = 0)
    {
        $this->component['gutter'] = $gutter;

        return $this;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'col' => Col::class,
        'text'=> Text::class,
        'card'=> Card::class,
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

            if($method == 'col') {
                $span = Arr::get($arguments, 0, ''); //[0];
                $argument = Arr::get($arguments, 1, ''); //[1];
                $element = new $className($span, $argument);
            }

            if($method == 'text') {
                $content = Arr::get($arguments, 0, ''); //[0];
                $element = new $className($content);
            }

            if($method == 'card') {
                $title = Arr::get($arguments, 0, ''); //[0];
                $argument = Arr::get($arguments, 1, ''); //[1];
                $element = new $className($title, $argument);
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

            $this->component['name'] = 'row';
            $this->component['items'][]= $element;
            return $element;
        }
    }
}
