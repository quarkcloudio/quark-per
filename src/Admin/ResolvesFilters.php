<?php

namespace QuarkCloudIO\QuarkAdmin;

use Illuminate\Http\Request;

trait ResolvesFilters
{
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
