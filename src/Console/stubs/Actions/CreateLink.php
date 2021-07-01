<?php

namespace App\Admin\Actions;

use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Actions\Link;

class CreateLink extends Link
{
    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'primary';

    /**
     * 设置图标
     *
     * @var string
     */
    public $icon = 'plus-circle';

    /**
     * 初始化
     *
     * @param  string  $name
     * 
     * @return void
     */
    public function __construct($name)
    {
        $this->name = '创建' . $name;
    }

    /**
     * 跳转链接
     *
     * @return string
     */
    public function href()
    {
        return '#/index?api=' . Str::replaceLast('/index', '/create', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }
}