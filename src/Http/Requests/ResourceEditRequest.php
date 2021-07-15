<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceEditRequest extends QuarkRequest
{
    /**
     * 表单数据
     *
     * @return object
     */
    public function fillData()
    {
        return $this->model()->find(request()->get('id'));
    }
}