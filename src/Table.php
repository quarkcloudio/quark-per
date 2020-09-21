<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;

class Table extends Element
{
    /**
     * 行主键
     *
     * @var string
     */
    public $rowKey = 'id';

    /**
     * 表头标题
     *
     * @var string
     */
    public $headerTitle;

    /**
     * 表格列的配置描述
     *
     * @var array
     */
    public $columns = [];
    
    /**
     * 表格数据
     *
     * @var array
     */
    public $datasource = [];

    /**
     * 绑定的模型
     *
     * @var object
     */
    public $model;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct($model = '')
    {
        $this->component = 'table';
        $this->model = $model;

        return $this;
    }

    /**
     * 表头标题
     *
     * @param  string  $headerTitle
     * @return $this
     */
    public function headerTitle($headerTitle)
    {
        $this->headerTitle = $headerTitle;

        return $this;
    }

    /**
     * 表格列的配置描述
     *
     * @param  array  $columns
     * @return $this
     */
    public function columns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * 读取或者设置表格模型
     *
     * @param  object|null  $model
     * @return $this
     */
    public function model($model = null)
    {
        if($model) {
            $this->model = $model;
        }

        return $this->model;
    }

    /**
     * 填充表格数据
     *
     * @param  arrar  $data
     * @return $this
     */
    public function fillData($data = null)
    {
        if($data) {
            $this->datasource = $data;
        }

        $this->datasource = $this->model;

        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {

        $this->fillData();

        return array_merge([
            'rowKey' => $this->rowKey,
            'headerTitle' => $this->headerTitle,
            'columns' => $this->columns,
            'datasource' => $this->datasource
        ], parent::jsonSerialize());
    }
}
