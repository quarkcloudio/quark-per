<?php

namespace QuarkCloudIO\Quark\Component\Lists;

use Closure;
use QuarkCloudIO\Quark\Component\Element;

/**
 * Class Meta.
 *
 */
class Meta extends Element
{
    /**
     * 列头显示文字，既字段的列名
     *
     * @var string
     */
    public $title = null;

    /**
     * 字段名称|字段的列名
     *
     * @var string
     */
    public $attribute = null;

    /**
     * 列数据在数据项中对应的路径，支持通过数组查询嵌套路径,与attribute相同
     *
     * @var string|array
     */
    public $dataIndex = null;

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
    public $search = false;

    /**
     * 是否为操作列
     * 
     * @var bool
     */
    public $actions = false;

    /**
     * 初始化
     *
     * @param  string  $attribute
     * @param  string  $title
     * @return void
     */
    public function __construct($attribute = null, $title = null)
    {
        $this->component = 'meta';
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
     * @param  bool  $search
     * @return $this
     */
    public function search($search = true)
    {
        $this->search = $search;

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
            'ellipsis' => $this->ellipsis,
            'copyable' => $this->copyable,
            'valueEnum' => $this->valueEnum,
            'valueType' => $this->valueType,
            'search' => $this->search,
            'align' => 'right'
        ], parent::jsonSerialize());
    }
}