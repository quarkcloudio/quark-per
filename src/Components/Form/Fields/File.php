<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class File extends Item
{
    public  $button,$limitSize,$limitType,$limitNum;

    function __construct($name,$label = '') {
        $this->component = 'file';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->mode = 'single';
        $this->button = '上传文件';
        $this->limitNum = 1; // 默认上传个数
        $this->limitSize = 2; // 默认文件大小2M
        $this->limitType = ['image/jpeg','image/png'];
    }

    public function mode($mode)
    {
        if($mode == 'multiple') {
            $this->limitNum = 3; // 默认多文件上传个数
        }

        $this->mode = $mode;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function limitSize($limitSize)
    {
        $this->limitSize = $limitSize;
        return $this;
    }

    public function limitType($limitType)
    {
        $this->limitType = $limitType;
        return $this;
    }

    public function limitNum($limitNum)
    {
        $this->limitNum = $limitNum;
        return $this;
    }

    public function button($text)
    {
        $this->button = $text;
        return $this;
    }
}
