<?php

namespace QuarkCloudIO\QuarkAdmin\Actions;

/**
 * Class Js.
 */
abstract class Js extends Action
{
    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、modal、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'js';

    /**
     * 执行的js语句
     *
     * @var string
     */
    public $js;

    /**
     * 执行的js语句
     *
     * @return string
     */
    public function js()
    {
        return $this->js;
    }
}
