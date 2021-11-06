<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceExportRequest extends QuarkRequest
{
    /**
     * 获取表格数据
     *
     * @return array|object
     */
    public function exportQuery()
    {
        $resource = $this->resource();
        
        return $resource::buildExportQuery(
            $this,
            $this->model(),
            $this->newResource()->searches($this),
            $this->newResource()->filters($this)
        )
        ->get();
    }
}
