<?php

namespace App\Admin\Actions;

class FormExtraBack extends FormBack
{
    /**
     * 行为名称，当行为在表格行展示时，支持js表达式
     *
     * @var string
     */
    public $name = "返回上一页";

    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'default';

    /**
     * 行为类型
     *
     * @var string
     */
    public $actionType = 'back';

    /**
     * 初始化
     *
     * @param  string  $name
     * 
     * @return void
     */
    public function __construct($name = null)
    {
        if($name) {
            $this->name = $name;
        }
        
        $this->type = 'link';
        $this->showOnFormExtra()->showOnDetailExtra();
    }
}