<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Requests;

class ResourceIndexRequest extends QuarkRequest
{
    /**
     * 获取表格数据
     *
     * @return array|object
     */
    public function indexQuery()
    {
        $resource = $this->resource();
        $query = $resource::buildIndexQuery(
                $this,
                $this->model(),
                $this->newResource()->searches($this),
                $this->newResource()->filters($this),
                $this->columnFilters(),
                $this->orderings()
            );

        if($resource::pagination()) {
            $result = $query->paginate(request('pageSize', $resource::pagination()));
        } else {
            $result = $query->get();
        }

        return $result;
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
