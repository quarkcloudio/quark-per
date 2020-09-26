<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Str;
use Illuminate\Database\Eloquent\Model as Eloquent;
use QuarkCMS\QuarkAdmin\Components\Table\Model;
use QuarkCMS\QuarkAdmin\Components\Table\Column;

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
    public $search = true;

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
     * eloquent模型
     *
     * @var object
     */
    public $eloquentModel;

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
        $this->eloquentModel = $this->model()->eloquent();
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
     * 读取模型
     *
     * @return $this
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * 获取表格行行为
     *
     * @return $this
     */
    public function getRowExecuteAction($id, $key)
    {
        $row = $this->eloquentModel->where('id',$id)->first()->toArray();
        $action = null;
        $columns = $this->columns;
        foreach ($columns as $columnKey => $value) {
            // 解析action回调函数
            if($value->actionCallback) {
                $actionCallback = call_user_func_array($value->actionCallback,[$row]);
                $rowActions = $actionCallback->actions();
                foreach ($rowActions as $rowActionKey => $rowActionValue) {
                    $actionValueArray = $rowActionValue->jsonSerialize();
                    if($key === $actionValueArray['key']) {
                        $action = $rowActionValue;
                    }
                }
            }
        }

        return $action;
    }

    /**
     * 执行行为
     *
     * @return $this
     */
    public function executeAction()
    {
        $id = request('id');
        $key = request('key');

        if(empty($id) || empty($key)) {
            return false;
        }

        $action = $this->getRowExecuteAction($id, $key);
        if(isset($action->model->queries)) {
            $action->model->queries->unique()->each(function ($query) use ($id) {
                if($id) {
                    foreach ($query['arguments'] as $key => $value) {
                        $query['arguments'][$key] = str_replace('{id}',$id,$value);
                    }
                }
                $this->eloquentModel = call_user_func_array([$this->eloquentModel, $query['method']], $query['arguments']);
            });
        }

        return true;
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

                // 解析using规则
                if($value->using) {
                    if(isset($value->using[$row[$value->name]])) {
                        $row[$value->name] = $value->using[$row[$value->name]];
                    }
                }

                // 解析link规则
                if($value->link) {
                    if($value->link === true) {
                        $item['title'] = $row[$value->name];
                        $action = \request()->route()->getName();
                        $action = Str::replaceFirst('api/','',$action);
                        $action = urlencode(Str::replaceLast('/index','/edit',$action));
                        $item['link'] = '/quark/engine?api='.$action.'&id='.$row['id'];
                        $row[$value->name] = $item;
                    } else {
                        $item['title'] = $row[$value->name];
                        $item['link'] = str_replace('{id}',$row['id'],$value->link);
                        $row[$value->name] = $item;
                    }
                }

                // 解析image规则
                if($value->image) {
                    if($value->image['path']) {
                        $row[$value->name] = $value->image['path'];
                    } else {
                        $row[$value->name] = get_picture($row[$value->name]);
                    }
                }

                // 解析qrcode规则
                if($value->qrcode) {
                    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=';
                    $size = $value->qrcode['width'].'x'.$value->qrcode['height'];
                    if($value->qrcode['content']) {
                        $content = '&data='.$value->qrcode['content'];
                    } else {
                        $content = '&data='.$row[$value->name];
                    }
                    $row[$value->name] = $url.$size.$content;
                }

                // 解析display回调函数
                if($value->displayCallback) {
                    $row[$value->name] = call_user_func_array($value->displayCallback,[$row[$value->name]]);
                }
            }

            // 解析action回调函数
            if($value->actionCallback) {
                $actionCallback = call_user_func_array($value->actionCallback,[$row]);
                $actions = $actionCallback->actions();
                foreach ($actions as $actionKey => $actionValue) {
                    $actionValueArray = $actionValue->jsonSerialize();
                    $actionValueArray['api'] = str_replace('{id}',$row['id'],$actionValueArray['api']);
                    $actions[$actionKey] = $actionValueArray;
                }
                $row[$value->name] = $actions;
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
        $this->key(__CLASS__.$this->headerTitle.json_encode($this->columns));

        // 填充数据
        $this->fillData();

        return array_merge([
            'rowKey' => $this->rowKey,
            'headerTitle' => $this->headerTitle,
            'columns' => $this->columns,
            'search' => $this->search,
            'datasource' => $this->datasource,
            'pagination' => $this->pagination
        ], parent::jsonSerialize());
    }
}
