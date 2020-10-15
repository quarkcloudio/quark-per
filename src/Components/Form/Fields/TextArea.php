<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use Illuminate\Support\Arr;
use Exception;

class TextArea extends Text
{
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
     * 初始化TextArea组件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */ 
    public function __construct($name,$label = '') {
        $this->component = 'textArea';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;
    }

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
            'placeholder' => $this->placeholder,
            'maxLength' => $this->maxLength,
            'autoSize' => $this->autoSize
        ], parent::jsonSerialize());
    }
}
