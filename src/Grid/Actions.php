<?php

namespace QuarkCMS\QuarkAdmin\Grid;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Closure;

class Actions
{
    // 操作

    public $action;

    protected $style = 'button';

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'add' => Actions\Add::class,
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
            $this->action['items'][] = $element;

            return $element;
        }
    }

    public function buttonStyle()
    {
        $this->style = 'button';
    }

    public function selectStyle()
    {
        $this->style = 'select';
    }

    public function dropdownStyle()
    {
        $this->style = 'dropdown';
    }

    public function render()
    {
        $this->action['style'] = $this->style;
        return $this->action;
    }
}
