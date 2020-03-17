<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Display extends Item
{
    public  $type;

    function __construct() {
        $this->componentName = 'display';
    }

    static function make($labelName)
    {
        $self = new self();

        $self->labelName = $labelName;

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }
}
