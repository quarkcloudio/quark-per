<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait ResolvesFilters
{
    /**
     * 获取字段上的过滤器
     *
     * @param  Request  $request
     * @return array
     */
    public function fieldFilters(Request $request)
    {
        return [];
    }
}
