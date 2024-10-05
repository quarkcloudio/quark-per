<?php

namespace QuarkCloudIO\Quark\Component\Descriptions\Fields;

use QuarkCloudIO\Quark\Component\Element;
use Exception;

class Item extends Element
{
    /**
     * 内容的描述
     *
     * @var string
     */
    public $label = null;

    /**
     * 内容的补充描述，hover 后显示
     *
     * @var string
     */
    public $tooltip = null;

    /**
     * 包含列的数量
     *
     * @var number
     */
    public $span = 1;

    /**
     * 格式化的类型
     *
     * @var string
     */
    public $valueType = 'text';

    /**
     * 当前列值的枚举
     *
     * @var string
     */
    public $valueEnum = null;

    /**
     * 返回数据的 key 与 ProDescriptions 的 request 配合使用，用于配置式的定义列表
     *
     * @var string
     */
    public $dataIndex = null;

    /**
     * 数值
     *
     * @var string
     */
    public $value = null;

    /**
     * 内容的描述
     *
     * @param  string $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * 内容的补充描述，hover 后显示
     *
     * @param  string $tooltip
     * @return $this
     */
    public function tooltip($tooltip)
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * 包含列的数量
     *
     * @param  number $span
     * @return $this
     */
    public function span($span)
    {
        $this->span = $span;
        return $this;
    }

    /**
     * 包含列的数量
     *
     * @param  string $dataIndex
     * @return $this
     */
    public function dataIndex($dataIndex)
    {
        $this->dataIndex = $dataIndex;
        return $this;
    }

    /**
     * 设置保存值。
     *
     * @param  array|string
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->label);

        return array_merge([
            'label' => $this->label,
            'tooltip' => $this->tooltip,
            'span' => $this->span,
            'valueType' => $this->valueType,
            'valueEnum' => $this->valueEnum,
            'dataIndex' => $this->dataIndex,
            'value' => $this->value
        ], parent::jsonSerialize());
    }
}
