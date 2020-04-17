<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Table
{
    public $component = null;

    function __construct($callback = null) {
        $this->component['name'] = 'table';

        if($callback) {
            $callback($this);
        }

        return $this;
    }

    public function columns($columns = '')
    {
        $this->component['columns'] = $columns;

        return $this;
    }

    public function showHeader($showHeader = true)
    {
        $this->component['showHeader'] = $showHeader;

        return $this;
    }

    public function pagination($pagination)
    {
        $this->component['pagination'] = $pagination;

        return $this;
    }

    public function dataSource($dataSource)
    {
        $this->component['dataSource'] = $dataSource;

        return $this;
    }

    public function size($size = 'default')
    {
        $this->component['size'] = $size;

        return $this;
    }
}
