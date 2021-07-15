<?php

namespace QuarkCMS\QuarkAdmin\Actions;

/**
 * Class Drawer.
 */
abstract class Drawer extends Action
{
    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、modal、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'drawer';

    /**
     * 宽度
     *
     * @var string | number
     */
    public $width = 520;

    /**
     * 宽度
     *
     * @return string
     */
    public function width()
    {
        return $this->width;
    }

    /**
     * 内容
     *
     * @return string
     */
    public function body()
    {
        return null;
    }

    /**
     * 弹窗行为
     *
     * @return $this
     */
    public function actions()
    {
        return null;
    }
}
