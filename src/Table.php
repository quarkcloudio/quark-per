<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use QuarkCMS\QuarkAdmin\Table\Model;
use QuarkCMS\QuarkAdmin\Table\Column;

use function GuzzleHttp\json_decode;

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
     * 是否开启搜索
     *
     * @var bool
     */
    public $search = false;

    /**
     * 表格数据
     *
     * @var array|string
     */
    public $datasource = null;

    /**
     * 表格分页
     *
     * @var array|bool
     */
    public $pagination = null;

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
     * 是否开启搜索
     *
     * @param  bool  $search
     * @return $this
     */
    public function search($search = true)
    {
        $this->search = $search;

        return $this;
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

        if(isset($data['current_page'])) {
            // 存在分页
            $pagination['defaultCurrent'] = 1;
            $pagination['current'] = $data['current_page'];
            $pagination['pageSize'] = $data['per_page'];
            $pagination['total'] = $data['total'];
            $this->pagination = $pagination;

            // 重设数据
            $data = $data['data'];
        }

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
        // 设置组件唯一标识
        $this->key($this->headerTitle.json_encode($this->columns));

        // 填充数据
        $this->fillData();

        return array_merge([
            'rowKey' => $this->rowKey,
            'headerTitle' => $this->headerTitle,
            'columns' => $this->parseColumns(),
            'search' => $this->search,
            'datasource' => $this->datasource,
            'pagination' => $this->pagination
        ], parent::jsonSerialize());
    }
}
