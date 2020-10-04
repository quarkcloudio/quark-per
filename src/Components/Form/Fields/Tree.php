<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
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

    public function data($treeData)
    {
        $this->treeData = $treeData;
        return $this;
    }
}
