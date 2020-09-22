<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use QuarkCMS\QuarkAdmin\Table\Model;
use QuarkCMS\QuarkAdmin\Table\Column;

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
     * 表格列描述
     *
     * @var object
     */
    public $columns;
    
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
    public function __construct(Eloquent $model)
    {
        $this->component = 'table';
        $this->model = new Model($model);
        $this->columns = collect();

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
     * 表格列描述
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    public function column($name, $label = '')
    {
        return $this->__call($name, array_filter([$label]));
    }

    /**
     * 解析列
     *
     * @return $this
     */
    protected function parseColumns()
    {
        $columns = $this->columns;
        foreach ($columns as $key => $value) {
            $getColumns[$key]['key'] = $value->name;
            $getColumns[$key]['title'] = $value->label;
            $getColumns[$key]['dataIndex'] = $value->name;
        }
        
        return $getColumns;
    }

    /**
     * 读取模型
     *
     * @return $this
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * 根据column显示规则解析每一行的数据
     *
     * @return $this
     */
    protected function parseRowData($row)
    {
        $columns = $this->columns;
        foreach ($columns as $key => $value) {
            if(isset($row[$value->name])) {

                // 解析display回调函数
                if($value->displayCallback) {
                    $row[$value->name] = call_user_func_array($value->displayCallback,[$row[$value->name]]);
                }

                // 解析using规则
                if($value->using) {
                    if(isset($value->using[$row[$value->name]])) {
                        $row[$value->name] = $value->using[$row[$value->name]];
                    }
                }
            }
        }

        return $row;
    }

    /**
     * 填充数据
     *
     * @param  array  $data
     * @return $this
     */
    public function fillData()
    {
        $data = $this->model->data();

        foreach ($data as $key => $value) {
            $datasource[$key] = $this->parseRowData($value);
        }

        $this->datasource = $datasource;
    }

    /**
     * Dynamically add columns to the table view.
     *
     * @param $method
     * @param $parameters
     *
     * @return Column
     */
    public function __call($method, $parameters)
    {
        $label = $parameters[0] ?? null;
        $column = new Column($method, $label);

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
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
            'columns' => $this->parseColumns(),
            'datasource' => $this->datasource
        ], parent::jsonSerialize());
    }
}
