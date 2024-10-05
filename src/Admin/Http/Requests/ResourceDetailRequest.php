<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Requests;

class ResourceDetailRequest extends QuarkRequest
{
    /**
     * 表单数据
     *
     * @return object
     */
    public function fillData()
    {
        $resource = $this->resource();
        $query = $resource::buildDetailQuery(
            $this,
            $this->model()
        );
        
        return $query->find(request()->get('id'));
    }
}