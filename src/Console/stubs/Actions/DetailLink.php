<?php

namespace App\Admin\Actions;

use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Actions\Link;

class DetailLink extends Link
{
    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'link';

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'small';

    /**
     * 初始化
     *
     * @param  string  $name
     * 
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 跳转链接
     *
     * @return string
     */
    public function href()
    {
        return '#/index?api=' . Str::replaceLast('/index', '/detail&id=${id}', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }
}