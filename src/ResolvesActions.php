<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait ResolvesActions
{
    /**
     * 行为
     *
     * @param  Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
