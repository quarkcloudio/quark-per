<?php

namespace QuarkCMS\QuarkAdmin\Grid;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Closure;

class BatchActions
{
    // 批量操作

    public $search;

    protected $expand = false;

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
        'scope' => Search\Fields\Scope::class,
        'group' => Search\Fields\Group::class,
        'where' => Search\Fields\Where::class,
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
            if($method == 'scope' || $method == 'group' || $method == 'where') {
                $element = new $className($column, array_slice($arguments, 1), $arguments[2]);
            } else {
                $element = new $className($column, array_slice($arguments, 1));
            }

            $this->search['items'][] = $element;

            return $element;
        }
    }

    public function expand($expand = true)
    {
        $this->expand = $expand;
    }

    public function render()
    {
        $this->search['expand'] = $this->expand;
        return $this->search;
    }
}
