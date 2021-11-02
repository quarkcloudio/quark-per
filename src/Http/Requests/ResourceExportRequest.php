<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceExportRequest extends QuarkRequest
{
    /**
     * 获取表格数据
     *
     * @return array|object
     */
    public function indexQuery()
    {
        $resource = $this->resource();
        
        return $resource::buildIndexQuery(
            $this,
            $this->model(),
            $this->newResource()->searches($this),
            $this->newResource()->filters($this)
        )
        ->get();
    }
}
