<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
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
     * 解析表格行执行行为
     *
     * @return $this
     */
    public function parseRowExecuteActionRules($actions,$key)
    {
        $action = null;

        foreach ($actions as $rowActionKey => $rowActionValue) {
            $actionValueArray = $rowActionValue->jsonSerialize();
            if($actionValueArray['component'] === 'dropdownStyle') {

                // 获取dropdown样式的行为
                if($actionValueArray['overlay']) {
                    foreach ($actionValueArray['overlay'] as $overlayKey => $overlayValue) {
                        $overlayActions = $overlayValue->actions();
                        if($overlayActions) {
                            $getAction = $this->parseRowExecuteActionRules($overlayActions,$key);
                            if($getAction) {
                                $action = $getAction;
                            }
                        }
                    }
                }
            } elseif($actionValueArray['component'] === 'selectStyle') {

                // 获取select样式的行为
                if($actionValueArray['options']) {
                    foreach ($actionValueArray['options'] as $optionKey => $optionValue) {
                        $optionActions = $optionValue->actions();
                        if($optionActions) {
                            $getAction = $this->parseRowExecuteActionRules($optionActions,$key);
                            if($getAction) {
                                $action = $getAction;
                            }
                        }
                    }
                }
            } else {

                // 获取a、button样式的行为
                if($key === $actionValueArray['key']) {
                    $action = $rowActionValue;
                }
            }
        }

        return $action;
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
                $action = $this->parseRowExecuteActionRules($rowActions,$key);
            }
        }

        return $action;
    }

    /**
     * 执行行为
     *
     * @return $this
     */
    public function executeRowAction()
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
     * 解析行行为规则
     *
     * @return $this
     */
    protected function parseRowActionRules($row,$actions)
    {
        foreach ($actions as $actionKey => $actionValue) {
            $actionValueArray = $actionValue->jsonSerialize();
            if($actionValueArray['component'] === 'dropdownStyle') {

                //解析dropdown样式
                if($actionValueArray['overlay']) {
                    foreach ($actionValueArray['overlay'] as $overlayKey => $overlayValue) {
                        $overlayActions = $overlayValue->actions();
                        if($overlayActions) {
                            $actionValueArray['overlay'] = $this->parseRowActionRules($row, $overlayActions);
                        }
                    }
                }
            } elseif($actionValueArray['component'] === 'selectStyle') {

                //解析select样式
                $actionValueArray['api'] = str_replace('{id}',$row['id'],$actionValueArray['api']);
                $actionValueArray['href'] = str_replace('{id}',$row['id'],$actionValueArray['href']);
            } else {

                //解析通用样式
                $actionValueArray['api'] = str_replace('{id}',$row['id'],$actionValueArray['api']);
                $actionValueArray['href'] = str_replace('{id}',$row['id'],$actionValueArray['href']);
            }

            $actions[$actionKey] = $actionValueArray;
        }

        return $actions;
    }

    /**
     * 解析每一行的行为
     *
     * @return $this
     */
    protected function parseRowActions($row)
    {
        $columns = $this->columns;
        foreach ($columns as $key => $value) {
            // 解析action回调函数
            if($value->actionCallback) {
                $actionCallback = call_user_func_array($value->actionCallback,[$row]);
                $actions = $actionCallback->actions();
                $row[$value->name] = $this->parseRowActionRules($row,$actions);
            }
        }

        return $row;
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
                    $item['title'] = $row[$value->name];
                    $item['href'] = str_replace('{id}',$row['id'],$value->link['href']);
                    $item['target'] = $value->link['target'];
                    $row[$value->name] = $item;
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
            // 解析每一行的行为
            $value = $this->parseRowActions($value);

            // 解析每一行的数据
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
