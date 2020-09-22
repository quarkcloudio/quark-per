<?php

namespace QuarkCMS\QuarkAdmin\Table;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Closure;

class Actions
{
    // 

    public $prefix = '';

    protected $action;

    public $items = [];

    protected $style = [];

    protected $showStyle = 'button';

    protected $placeholder;

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'button' => Actions\Button::class,
        'option' => Actions\Option::class,
        'menu' => Actions\Menu::class,
    ];

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function style($showStyle,$style = [])
    {
        $this->style = $style;
        $this->showStyle = $showStyle;
        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function render()
    {
        $this->action['items'] = $this->items;
        $this->action['style'] = $this->style;
        $this->action['showStyle'] = $this->showStyle;
        $this->action['placeholder'] = $this->placeholder;
        return $this->action;
    }

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

            if($method == 'button' || $method == 'menu' || $method == 'option') {
                $element = new $className($column, array_slice($arguments, 1), $this->prefix);
            } else {
                $element = new $className($column, array_slice($arguments, 1));
            }

            $this->items[] = $element;
            return $element;
        }
    }
}
