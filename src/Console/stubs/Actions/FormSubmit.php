<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class FormSubmit extends Action
{
    /**
     * 行为名称，当行为在表格行展示时，支持js表达式
     *
     * @var string
     */
    public $name = "提交";

    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'primary';

    /**
     * 行为类型
     *
     * @var string
     */
    public $actionType = 'submit';

    /**
     * 是否具有loading，当action 的作用类型为ajax,submit时有效
     *
     * @var bool
     */
    public $withLoading = true;

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

        $this->onlyOnForm();
    }
}