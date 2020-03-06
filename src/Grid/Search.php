<?php

namespace QuarkCMS\QuarkAdmin\Grid;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Closure;

class Search
{
    public $search;

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'equal' => Search\Fields\Equal::class,
        'like' => Search\Fields\Like::class,
        'between' => Search\Fields\Between::class,
        'gt' => Search\Fields\Gt::class,
        'lt' => Search\Fields\Lt::class,
        'in' => Search\Fields\In::class,
        'notIn' => Search\Fields\NotIn::class,
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
            $this->search['items'][] = $element;

            return $element;
        }
    }

    public function render()
    {
        return $this->search;
    }
}
