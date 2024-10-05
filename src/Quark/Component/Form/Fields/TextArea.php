<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

class TextArea extends Text
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'textAreaField';

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['width' => 400];

    /**
     * autoSize 属性适用于 textarea 节点，并且只有高度会自动变化。另外 autoSize 可以设定为一个对象，指定最小行数和最大行数。
     *
     * @var bool|array
     */
    public $autoSize = [];

    /**
     * 指定最小行数。
     *
     * @var number
     */
    public $minRows = 2;

    /**
     * 指定最大行数。
     *
     * @var number
     */
    public $maxRows = 5;

    /**
     * autoSize 属性适用于 textarea 节点，并且只有高度会自动变化。另外 autoSize 可以设定为一个对象，指定最小行数和最大行数。
     *
     * @param  bool|array $autosize
     * @return $this
     */
    public function autoSize($autoSize)
    {
        $this->autoSize = $autoSize;
        return $this;
    }

    /**
     * 指定最小行数。
     *
     * @param  number $rows
     * @return $this
     */
    public function minRows($rows)
    {
        $this->minRows = $rows;
        return $this;
    }

    /**
     * 指定最大行数。
     *
     * @param  number $rows
     * @return $this
     */
    public function maxRows($rows)
    {
        $this->maxRows = $rows;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if($this->minRows) {
            $this->autoSize['minRows'] = $this->minRows;
        }

        if($this->maxRows) {
            $this->autoSize['maxRows'] = $this->maxRows;
        }

        return array_merge([
            'placeholder' => $this->placeholder ?? '请输入' . $this->label,
            'maxLength' => $this->maxLength,
            'autoSize' => $this->autoSize
        ], parent::jsonSerialize());
    }
}
