<?php

namespace QuarkCloudIO\Quark\Component\Table;

use QuarkCloudIO\Quark\Component\Table\ToolBar;
use QuarkCloudIO\Quark\Component\Table\Column;
use QuarkCloudIO\Quark\Component\Table\Search;
use QuarkCloudIO\Quark\Component\Element;
use Closure;
use Exception;

class Table extends Element
{
    /**
     * 行主键
     *
     * @var string
     */
    public $rowKey = 'id';

    /**
     * 获取表格数据接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 获取表格数据接口类型
     *
     * @var string
     */
    public $apiType = 'GET';

    /**
     * 表格元素的 table-layout 属性，设为 fixed 表示内容不会影响列的布局
     *
     * @var string
     */
    public $tableLayout = null;

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
     * 批量操作选择项
     *
     * @var array
     */
    public $rowSelection = [];

    /**
     * table 工具栏，设为 false 时不显示,{{ fullScreen: true, reload: true ,setting: true }}
     *
     * @var array
     */
    public $options = ['fullScreen' => true, 'reload' => true, 'setting' => true];

    /**
     * 是否自动维护搜索表单
     *
     * @var bool
     */
    public $autoBuildSearchFrom = false;

    /**
     * 是否显示搜索表单，传入对象时为搜索表单的配置
     *
     * @var array
     */
    public $search = [];

    /**
     * 表格的批量操作行为
     *
     * @var object
     */
    public $batchActions = false;

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
     * @var object
     */
    public $toolBar = null;

    /**
     * 自定义表格的主体函数
     *
     * @var array
     */
    public $tableExtraRender = null;

    /**
     * 设置表格滚动
     *
     * @var array
     */
    public $scroll = null;

    /**
     * 设置显示斑马线样式
     *
     * @var bool
     */
    public $striped = false;

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
    public $pagination = false;

    /**
     * 是否轮询
     *
     * @var null|number
     */
    public $polling = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct()
    {
        $this->component = 'table';
        $this->search = new Search;

        return $this;
    }

    /**
     * 获取表格数据接口
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * 获取表格数据接口类型
     *
     * @param  string  $apiType
     * @return $this
     */
    public function apiType($apiType)
    {
        $this->apiType = $apiType;

        return $this;
    }

    /**
     * 表格元素的 table-layout 属性，设为 fixed 表示内容不会影响列的布局,- | auto | fixed
     *
     * @param  string  $tableLayout
     * @return $this
     */
    public function tableLayout($tableLayout)
    {
        if(!in_array($tableLayout,['auto', 'fixed'])) {
            throw new Exception("argument must be in 'auto', 'fixed'!");
        }
        $this->tableLayout = $tableLayout;

        return $this;
    }

    /**
     * 表头标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->headerTitle($title);

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
     * 是否显示搜索表单，传入对象时为搜索表单的配置
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function search(Closure $callback = null)
    {
        $callback($this->search);

        return $this->search;
    }

    /**
     * 搜索表单的配置
     *
     * @param  array  $search
     * @return $this
     */
    public function searches($search = [])
    {
        $this->search = $search;

        return $this;
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

        return $this;
    }

    /**
     * 批量操作选择项
     *
     * @param array $rowSelection
     * @return $this
     */
    public function rowSelection($rowSelection)
    {
        $this->rowSelection = $rowSelection;

        return $this;
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
     * @param  void
     * @return $this
     */
    public function toolBar($toolBar)
    {
        $this->toolBar = $toolBar;

        return $this;
    }

    /**
     * 表格的批量操作
     *
     * @param  array  $batchActions
     * @return $this
     */
    public function batchActions($batchActions)
    {
        $this->batchActions = $batchActions;

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
     * 设置表格滚动
     *
     * @param  array  $scroll
     * @return $this
     */
    public function scroll($scroll)
    {
        $this->scroll = $scroll;

        return $this;
    }

    /**
     * 设置表格滚动
     *
     * @param  bool  $striped
     * @return $this
     */
    public function striped($striped = true)
    {
        $this->striped = $striped;

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
     * 是否轮询
     *
     * @param  null|number  $polling
     * @return $this
     */
    public function polling($polling)
    {
        $this->polling = $polling;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->headerTitle.json_encode($this->columns), true);
        }

        return array_merge([
            'api' => $this->api,
            'apiType' => strtoupper($this->apiType),
            'rowKey' => $this->rowKey,
            'tableLayout' => $this->tableLayout,
            'headerTitle' => $this->headerTitle,
            'columns' => $this->columns,
            'rowSelection' => $this->rowSelection,
            'options' => $this->options,
            'autoBuildSearchFrom' => $this->autoBuildSearchFrom,
            'search' => $this->search,
            'batchActions' => $this->batchActions,
            'toolBar' => $this->toolBar,
            'dateFormatter' => $this->dateFormatter,
            'columnEmptyText' => $this->columnEmptyText,
            'tableExtraRender' => $this->tableExtraRender,
            'scroll' => $this->scroll,
            'striped' => $this->striped,
            'datasource' => $this->datasource,
            'pagination' => $this->pagination,
            'polling' => $this->polling
        ], parent::jsonSerialize());
    }
}