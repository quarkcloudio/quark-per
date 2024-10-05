<?php

namespace QuarkCloudIO\Quark\Component\Table;

use Closure;
use QuarkCloudIO\Quark\Component\Element;

/**
 * Class Column.
 *
 */
class Column extends Element
{
    /**
     * 列头显示文字，既字段的列名
     *
     * @var string
     */
    public $title;

    /**
     * 字段名称|字段的列名
     *
     * @var string
     */
    public $attribute = null;

    /**
     * 设置列的对齐方式,left | right | center
     *
     * @var string
     */
    public $align = 'left';

    /**
     * 列数据在数据项中对应的路径，支持通过数组查询嵌套路径,与attribute相同
     *
     * @var string|array
     */
    public $dataIndex = null;

    /**
     * （IE 下无效）列是否固定，可选 true (等效于 left) left right
     *
     * @var bool | string
     */
    public $fixed = false;

    /**
     * 会在 title 之后展示一个 icon，hover 之后提示一些信息
     *
     * @var string
     */
    public $tooltip = null;

    /**
     * 是否自动缩略
     *
     * @var bool
     */
    public $ellipsis = false;

    /**
     * 是否支持复制
     *
     * @var bool
     */
    public $copyable = false;

    /**
     * 值的枚举，会自动转化把值当成 key 来取出要显示的内容
     *
     * @var array
     */
    public $valueEnum = null;

    /**
     * 值的类型,'money' | 'option' | 'date' | 'dateTime' | 'time' | 'text'| 'index' | 'indexBorder'
     *
     * @var string
     */
    public $valueType = 'text';

    /**
     * 在查询表单中不展示此项
     *
     * @var bool
     */
    public $hideInSearch = false;

    /**
     * 在 Table 中不展示此列
     *
     * @var bool
     */
    public $hideInTable = false;

    /**
     * 在 Form 模式下 中不展示此列
     *
     * @var bool
     */
    public $hideInForm = false;

    /**
     * 表头的筛选菜单项，当值为 true 时，自动使用 valueEnum 生成
     *
     * @var bool|object
     */
    public $filters = false;

    /**
     * 查询表单中的权重，权重大排序靠前
     *
     * @var number
     */
    public $order = false;

    /**
     * 可排序列
     * 
     * @var array|bool
     */
    public $sorter = false;

    /**
     * 列宽
     *
     * @var string|number
     */
    public $width;

    /**
     * 可编辑列
     * 
     * @var array|bool
     */
    public $editable = false;

    /**
     * 是否为操作列
     * 
     * @var bool
     */
    public $actions = false;

    /**
     * 传递给 Form.Item 的配置,可以配置 rules，但是默认的查询表单 rules 是不生效的。需要配置 ignoreRules
     *
     * @var array
     */
    public $formItemProps = null;

    /**
     * 初始化
     *
     * @param  string  $attribute
     * @param  string  $title
     * @return void
     */
    public function __construct($attribute = null, $title = null)
    {
        $this->component = 'column';
        $this->dataIndex = $this->attribute = $attribute;

        $title ? $this->title = $title : $this->title = $attribute;
        
        return $this;
    }

    /**
     * 列头显示文字，既字段的列名
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 字段名称|字段的列名
     *
     * @param  string  $attribute
     * @return $this
     */
    public function attribute($attribute)
    {
        $this->dataIndex = $this->attribute = $attribute;
        return $this;
    }

    /**
     * 设置列的对齐方式,left | right | center
     *
     * @param  string  $align
     * @return $this
     */
    public function align($align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * （IE 下无效）列是否固定，可选 true (等效于 left) left right
     *
     * @param  bool|string  $fixed
     * @return $this
     */
    public function fixed($fixed = true)
    {
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * 会在 title 之后展示一个 icon，hover 之后提示一些信息
     *
     * @param  bool|string  $tooltip
     * @return $this
     */
    public function tooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * 是否自动缩略
     *
     * @param  bool  $ellipsis
     * @return $this
     */
    public function ellipsis($ellipsis = true)
    {
        $this->ellipsis = $ellipsis;
        return $this;
    }
    
    /**
     * 是否支持复制
     *
     * @param  bool  $copyable
     * @return $this
     */
    public function copyable($copyable = true)
    {
        $this->copyable = $copyable;
        return $this;
    }

    /**
     * 值的枚举，会自动转化把值当成 key 来取出要显示的内容
     *
     * @param  array  $valueEnum
     * @return $this
     */
    public function valueEnum($valueEnum)
    {
        $this->valueEnum = $valueEnum;
        return $this;
    }

    /**
     * 值的类型,'money' | 'option' | 'date' | 'dateTime' | 'time' | 'text'| 'index' | 'indexBorder'
     *
     * @param  string  $valueType
     * @return $this
     */
    public function valueType($valueType)
    {
        $this->valueType = $valueType;
        return $this;
    }

    /**
     * 在查询表单中不展示此项
     *
     * @param  bool  $hideInSearch
     * @return $this
     */
    public function hideInSearch($hideInSearch = true)
    {
        $this->hideInSearch = $hideInSearch;
        return $this;
    }

    /**
     * 在 Table 中不展示此列
     *
     * @param  bool  $hideInTable
     * @return $this
     */
    public function hideInTable($hideInTable = true)
    {
        $this->hideInTable = $hideInTable;
        return $this;
    }

    /**
     * 表头的筛选菜单项，当值为 true 时，自动使用 valueEnum 生成
     *
     * @param  array|bool  $filters
     * @return $this
     */
    public function filters($filters = [])
    {
        if(is_bool($filters)) {
            $getFilters = $filters;
        } else {
            $getFilters = [];
            foreach ($filters as $key => $value) {
                $filter['text'] = $value;
                $filter['value'] = $key;
                $getFilters[] = $filter;
            }
        }

        $this->filters = $getFilters;
        return $this;
    }

    /**
     * 查询表单中的权重，权重大排序靠前
     *
     * @param  number  $order
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * 可排序列
     *
     * @param  bool  $sorter
     * @return $this
     */
    public function sorter($sorter = true)
    {
        $this->sorter = $sorter;
        return $this;
    }

    /**
     * 设置列宽
     *
     * @param  string|number  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * 设置为可编辑列
     *
     * @param  string  $name
     * @param  array|bool  $options
     * @param  string  $action
     * @return $this
     */
    public function editable($name='text',$options=false,$action='')
    {
        if(empty($action)) {
            $subject = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $startPosition = strpos($subject, '/api/');
            $action = ($startPosition !== false) ? substr_replace($subject, '', $startPosition, strlen('/api/')) : $subject;
    
            $endPosition = strrpos($action, '/index');
            $action = ($endPosition !== false) ? substr_replace($action, '/editable', $endPosition, strlen('/index')) : $action;
        }

        if($name == 'select') {
            $getOptions = [];
            foreach ($options as $key => $value) {
                $option['label'] = $value;
                $option['value'] = $key;
                $getOptions[] = $option;
            }

            $options = $getOptions;
        }

        $editable['name'] = $name;
        $editable['options'] = $options;
        $editable['action'] = $action;
        
        $this->editable = $editable;
        return $this;
    }

    /**
     * 设置为数据操作列
     *
     * @param  array  $actions
     * @return $this
     */
    public function actions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * 传递给 Form.Item 的配置,可以配置 rules，但是默认的查询表单 rules 是不生效的。需要配置 ignoreRules
     *
     * @param  $formItemProps
     * @return $this
     */
    public function formItemProps($formItemProps)
    {
        $this->formItemProps = $formItemProps;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key = $this->dataIndex;

        return array_merge([
            'title' => $this->title,
            'dataIndex' => $this->dataIndex,
            'align' => $this->align,
            'fixed' => $this->fixed,
            'tooltip' => $this->tooltip,
            'ellipsis' => $this->ellipsis,
            'copyable' => $this->copyable,
            'valueEnum' => $this->valueEnum,
            'valueType' => $this->valueType,
            'hideInSearch' => $this->hideInSearch,
            'hideInTable' => $this->hideInTable,
            'filters' => $this->filters,
            'order' => $this->order,
            'sorter' => $this->sorter,
            'width' => $this->width,
            'actions' => $this->actions,
            'editable' => $this->editable,
            'formItemProps' => $this->formItemProps
        ], parent::jsonSerialize());
    }
}