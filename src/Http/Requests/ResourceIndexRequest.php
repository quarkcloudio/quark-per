<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

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
                $this->newResource()->filters($this)
            );

        if($resource::pagination()) {
            $result = $query->paginate(request('pageSize', $resource::pagination()));
        } else {
            $result = $query->get();
        }

        return $result;
    }
}
