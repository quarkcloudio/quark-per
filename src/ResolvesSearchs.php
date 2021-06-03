<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait ResolvesSearchs
{
    /**
     * 搜索表单
     *
     * @param  Request  $request
     * @return array
     */
    public function searches(Request $request)
    {
        return [];
    }
}
