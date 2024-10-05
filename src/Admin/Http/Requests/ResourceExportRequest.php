<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Requests;

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
            $this->newResource()->filters($this),
            $this->columnFilters(),
            $this->orderings()
        )
        ->get();
    }

    /**
     * Get the column filters for the request.
     *
     * @return array
     */
    public function columnFilters()
    {
        return ! empty($this->filter) && is_array($this->filter)
                        ? $this->filter
                        : [];
    }

    /**
     * Get the orderings for the request.
     *
     * @return array
     */
    public function orderings()
    {
        return ! empty($this->sorter) && is_array($this->sorter)
                        ? $this->sorter
                        : [];
    }
}
