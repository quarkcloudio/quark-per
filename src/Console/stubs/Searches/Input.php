<?php

namespace App\Admin\Searches;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Searches\Search;

class Input extends Search
{
    /**
     * 初始化
     *
     * @param  string  $column
     * @return void
     */
    public function __construct($column, $name)
    {
        $this->column = $column;
        $this->name = $name;
    }

    /**
     * 执行查询
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where($this->column, 'like', '%'.$value.'%');
    }
}