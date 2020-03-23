<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Tree extends Item
{
    public  $treeData;

    function __construct($name,$label = '') {
        $this->component = 'tree';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
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

    public function data($treeData)
    {
        $this->treeData = $treeData;
        return $this;
    }
}
