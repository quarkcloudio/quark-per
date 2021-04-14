<?php

namespace QuarkCMS\QuarkAdmin\Components\Table;

use Closure;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Element;

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
     * 是否渲染Html
     *
     * @var bool
     */
    public $isHtml = false;

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
     * using规则
     * 
     * @var array
     */
    public $using;

    /**
     * 列以链接形式展示
     * 
     * @var string|bool
     */
    public $link;

    /**
     * 列以图片形式展示
     * 
     * @var string|bool
     */
    public $image;

    /**
     * 列以二维码形式展示
     * 
     * @var string|bool
     */
    public $qrcode;

    /**
     * 数据展示回调
     *
     * @var
     */
    public $displayCallback = null;

    /**
     * 是否为操作列
     * 
     * @var bool
     */
    public $actions = false;

    /**
     * 数据操作回调
     *
     * @var
     */
    public $actionCallback = null;

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
     * 是否渲染Html
     *
     * @param  bool  $isHtml
     * @return $this
     */
    public function isHtml($isHtml = true)
    {
        $this->isHtml = $isHtml;
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
     * @param  array  $filters
     * @return $this
     */
    public function filters($filters = [])
    {
        $getFilters = [];
        foreach ($filters as $key => $value) {
            $filter['text'] = $value;
            $filter['value'] = $key;
            $getFilters[] = $filter;
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
            $action = \request()->route()->getName();
            $action = Str::replaceFirst('api/','',$action);
            $action = Str::replaceLast('/index','/action',$action);
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
     * 设置本列跳转链接
     *
     * @param  string|bool  $href
     * @param  string  $target
     * @return $this
     */
    public function link($href='', $target='_self')
    {
        if(!in_array($target,['_blank', '_self', '_parent', '_top'])) {
            throw new \Exception("Argument must be in '_blank', '_self', '_parent', '_top'!");
        }

        $link['href'] = $href;
        $link['target'] = $target;
        $this->link = $link;
        return $this;
    }

    /**
     * 设置本列编辑的跳转链接
     *
     * @param  string  $target
     * @return $this
     */
    public function editLink($target='_self')
    {
        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/index','/edit',$action);
        $href = '/quark/engine?api='.$action.'&id={id}';
        $this->link($href, $target);

        return $this;
    }

    /**
     * 设置本列using规则
     *
     * @param  array  $using
     * @return $this
     */
    public function using($using)
    {
        $this->using = $using;
        return $this;
    }

    /**
     * 设置本列图片显示
     *
     * @param  string  $path
     * @param  string|number  $width
     * @param  string|number  $height
     * @return $this
     */
    public function image($path=null,$width=25,$height=25)
    {
        $image['path'] = $path;
        $image['width'] = $width;
        $image['height'] = $height;
        $this->image = $image;
        return $this;
    }

    /**
     * 设置本列二维码显示
     *
     * @param  string  $content
     * @param  string|number  $width
     * @param  string|number  $height
     * @return $this
     */
    public function qrcode($content=null,$width=150,$height=150)
    {
        $qrcode['content'] = $content;
        $qrcode['width'] = $width;
        $qrcode['height'] = $height;
        $this->qrcode = $qrcode;
        return $this;
    }

    /**
     * 重置列的数据显示
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function display(Closure $callback)
    {
        $this->displayCallback = $callback;
        return $this;
    }

    /**
     * 设置为数据操作列
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function actions(Closure $callback = null)
    {
        $this->actions = true;
        $this->actionCallback = $callback;
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
            'isHtml' => $this->isHtml,
            'valueEnum' => $this->valueEnum,
            'valueType' => $this->valueType,
            'hideInSearch' => $this->hideInSearch,
            'hideInTable' => $this->hideInTable,
            'filters' => $this->filters,
            'order' => $this->order,
            'sorter' => $this->sorter,
            'width' => $this->width,
            'link' => $this->link,
            'image' => $this->image,
            'qrcode' => $this->qrcode,
            'actions' => $this->actions,
            'editable' => $this->editable
        ], parent::jsonSerialize());
    }
}