<?php

namespace App\Admin\Searches;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Searches\DateTimeRange as BaseDateTimeRange;

class DateTimeRange extends BaseDateTimeRange
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
     * @param  Request  $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->whereBetween($this->column, $value);
    }
}