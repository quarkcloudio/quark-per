<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class TimeRange extends Item
{
    public  $format,
            $showTime;

    function __construct($name,$label = '') {
        $this->component = 'timeRange';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->format = 'HH:mm';
        $this->value = [null,null];
    }

    /**
     * 创建组件
     *
     * @param  string $name
     * @param  string $label
     * @return object
     */
    static function make($name,$label = '')
    {
        $self = new self();

        $self->name = $name;
        if(empty($label)) {
            $self->label = $name;
        } else {
            $self->label = $label;
        }

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}
