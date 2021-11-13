<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Dropdown;

class MoreActions extends Dropdown
{
    /**
     * 下拉框箭头是否显示
     *
     * @var bool
     */
    public $arrow = true;

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @var string
     */
    public $placement = 'bottomLeft';

    /**
     * 触发下拉的行为, 移动端不支持 hover,Array<click|hover|contextMenu>
     *
     * @var bool
     */
    public $trigger = ['hover'];

    /**
     * 下拉根元素的样式
     *
     * @var array
     */
    public $overlayStyle = ['zIndex' => 999];

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
}