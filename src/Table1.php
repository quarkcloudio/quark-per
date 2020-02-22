<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Closure;

class Table
{
    public $table;

    public $model;

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'column' => Table\Column::class,
    ];

    /**
     * Create a new table instance.
     *
     * @param $model
     * @param \Closure $callback
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * table title.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function title($title)
    {
        $this->table['title'] = $title;
        return $this;
    }

    /**
     * table disableCreateButton.
     *
     * @return bool
     */
    public function disableCreateButton()
    {
        $this->table['disableCreateButton'] = true;
        return $this;
    }

    /**
     * table disablePagination.
     *
     * @return bool
     */
    public function disablePagination()
    {
        $this->table['disablePagination'] = true;
        return $this;
    }

    /**
     * table disableSearch.
     *
     * @return bool
     */
    public function disableSearch()
    {
        $this->table['disableSearch'] = true;
        return $this;
    }

    /**
     * table disableAdvancedSearch.
     *
     * @return bool
     */
    public function disableAdvancedSearch()
    {
        $this->table['disableAdvancedSearch'] = true;
        return $this;
    }

    /**
     * table 禁用查询过滤器
     *
     * @return bool
     */
    public function disableFilter()
    {
        $this->table['disableFilter'] = true;
        return $this;
    }

    /**
     * table 禁用导出数据按钮
     *
     * @return bool
     */
    public function disableExport()
    {
        $this->table['disableExport'] = true;
        return $this;
    }

    /**
     * table 禁用行选择checkbox
     *
     * @return bool
     */
    public function disableRowSelector()
    {
        $this->table['disableRowSelector'] = true;
        return $this;
    }

    /**
     * table 禁用行操作列
     *
     * @return bool
     */
    public function disableActions()
    {
        $this->table['disableActions'] = true;
        return $this;
    }

    /**
     * table 禁用行选择器
     *
     * @return bool
     */
    public function disableColumnSelector()
    {
        $this->table['disableColumnSelector'] = true;
        return $this;
    }

    /**
     * table model.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * table pagination.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function pagination($pagination)
    {
        $this->table['pagination'] = $pagination;
        return $this;
    }

    /**
     * table size.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function size($size)
    {
        $this->table['size'] = $size;
        return $this;
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

    public function __call($method, $arguments) {
        if ($className = static::findFieldClass($method)) {

            $column = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($column, array_slice($arguments, 1));
            $this->table['columns'][] = $element;

            return $element;
        }
    }
}
