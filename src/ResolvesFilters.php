<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait ResolvesFilters
{
    /**
     * 表格列上的筛选表单
     *
     * @param  Request  $request
     * @return array
     */
    public function tableColumnFilters(Request $request)
    {
        return [];
    }

    /**
     * 定义筛选表单
     *
     * @param  Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }
}
