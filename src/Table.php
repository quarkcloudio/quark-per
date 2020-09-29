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
     * table 工具栏，设为 false 时不显示,{{ fullScreen: true, reload: true ,setting: true }}
     *
     * @var array
     */
    public $options = ['fullScreen' => true, 'reload' => true, 'setting' => true];

    /**
     * 是否显示搜索表单，传入对象时为搜索表单的配置
     *
     * @var bool|array
     */
    public $search = true;

    /**
     * 转化 moment 格式数据为特定类型，false 不做转化,"string" | "number" | false
     *
     * @var bool|string
     */
    public $dateFormatter = 'string';

    /**
     * 空值时的显示，不设置 则默认显示 -
     *
     * @var bool|string
     */
    public $columnEmptyText = '-';

    /**
     * 透传 ProUtils 中的 ListToolBar 配置项
     *
     * @var array
     */
    public $toolbar = null;

    /**
     * 自定义表格的主体函数
     *
     * @var array
     */
    public $tableExtraRender = null;

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
     * @param string $attribute
     * @param string $title
     * @return Column
     */
    public function column($attribute, $title = '')
    {
        return $this->__call($attribute, array_filter([$title]));
    }

    /**
     * 批量设置表格列
     *
     * @param array $columns
     * @return $this
     */
    public function columns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * table 工具栏，设为 false 时不显示,{ fullScreen: true, reload: true ,setting: true}
     *
     * @param  array|bool  $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * 是否显示搜索表单，传入对象时为搜索表单的配置
     *
     * @param  bool|array  $search
     * @return $this
     */
    public function search($search = true)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * 转化 moment 格式数据为特定类型，false 不做转化,"string" | "number" | false
     *
     * @param  string  $dateFormatter
     * @return $this
     */
    public function dateFormatter($dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;

        return $this;
    }

    /**
     * 空值时的显示，不设置 则默认显示 -
     *
     * @param  string  $columnEmptyText
     * @return $this
     */
    public function columnEmptyText($columnEmptyText)
    {
        $this->columnEmptyText = $columnEmptyText;

        return $this;
    }

    /**
     * 透传 ProUtils 中的 ListToolBar 配置项
     *
     * @param  array  $toolbar
     * @return $this
     */
    public function toolbar($toolbar)
    {
        $this->toolbar = $toolbar;

        return $this;
    }

    /**
     * 自定义表格的主体函数
     *
     * @param  array  $tableExtraRender
     * @return $this
     */
    public function tableExtraRender($tableExtraRender)
    {
        $this->tableExtraRender = $tableExtraRender;

        return $this;
    }

    /**
     * 表格数据
     *
     * @param  array|string  $datasource
     * @return $this
     */
    public function datasource($datasource)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * 表格分页
     *
     * @param  number  $current
     * @param  number  $pageSize
     * @param  number  $total
     * @param  number  $defaultCurrent
     * @return $this
     */
    public function pagination($current, $pageSize, $total, $defaultCurrent = 1)
    {
        $pagination['defaultCurrent'] = $defaultCurrent;
        $pagination['current'] = $current;
        $pagination['pageSize'] = $pageSize;
        $pagination['total'] = $total;

        $this->pagination = $pagination;

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
     * @param  array  $actions
     * @param  string  $key
     * @return array
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
     * @param  string|number  $id
     * @param  string  $key
     * @return object
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
     * @return bool
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
     * @param  array  $row
     * @param  object  $actions
     * @return array
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
     * @param  array  $row
     * @return array
     */
    protected function parseRowActions($row)
    {
        $columns = $this->columns;
        foreach ($columns as $key => $value) {
            // 解析action回调函数
            if($value->actionCallback) {
                $actionCallback = call_user_func_array($value->actionCallback,[$row]);
                $actions = $actionCallback->actions();
                $row[$value->attribute] = $this->parseRowActionRules($row,$actions);
            }
        }

        return $row;
    }

    /**
     * 根据column显示规则解析每一行的数据
     *
     * @param  array  $row
     * @return array
     */
    protected function parseRowData($row)
    {
        $columns = $this->columns;
        foreach ($columns as $key => $value) {
            if(isset($row[$value->attribute])) {

                // 解析using规则
                if($value->using) {
                    if(isset($value->using[$row[$value->attribute]])) {
                        $row[$value->attribute] = $value->using[$row[$value->attribute]];
                    }
                }

                // 解析link规则
                if($value->link) {
                    $item['title'] = $row[$value->attribute];
                    $item['href'] = str_replace('{id}',$row['id'],$value->link['href']);
                    $item['target'] = $value->link['target'];
                    $row[$value->attribute] = $item;
                }

                // 解析image规则
                if($value->image) {
                    if($value->image['path']) {
                        $row[$value->attribute] = $value->image['path'];
                    } else {
                        $row[$value->attribute] = get_picture($row[$value->attribute]);
                    }
                }

                // 解析qrcode规则
                if($value->qrcode) {
                    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=';
                    $size = $value->qrcode['width'].'x'.$value->qrcode['height'];
                    if($value->qrcode['content']) {
                        $content = '&data='.$value->qrcode['content'];
                    } else {
                        $content = '&data='.$row[$value->attribute];
                    }
                    $row[$value->attribute] = $url.$size.$content;
                }

                // 解析display回调函数
                if($value->displayCallback) {
                    $row[$value->attribute] = call_user_func_array($value->displayCallback,[$row[$value->attribute]]);
                }
            }
        }

        return $row;
    }

    /**
     * 填充数据
     *
     * @return $this
     */
    public function fillData()
    {
        if(!empty($this->datasource)) {
            $data = $this->datasource;
        } else {
            $data = $this->model->data();
        }

        if(isset($data['current_page'])) {
            // 存在分页，则设置分页
            $this->pagination($data['current_page'], $data['per_page'], $data['total']);

            // 重设数据
            $data = $data['data'];
        }

        $datasource = null;
        foreach ($data as $key => $value) {
            // 解析每一行的行为
            $value = $this->parseRowActions($value);

            // 解析每一行的数据
            $datasource[$key] = $this->parseRowData($value);
        }

        $this->datasource($datasource);
    }

    /**
     * 动态添加列
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
     * 组件json序列化
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
            'options' => $this->options,
            'search' => $this->search,
            'dateFormatter' => $this->dateFormatter,
            'columnEmptyText' => $this->columnEmptyText,
            'toolbar' => $this->toolbar,
            'tableExtraRender' => $this->tableExtraRender,
            'datasource' => $this->datasource,
            'pagination' => $this->pagination
        ], parent::jsonSerialize());
    }
}
