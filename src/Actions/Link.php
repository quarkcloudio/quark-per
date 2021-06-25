<?php

namespace QuarkCMS\QuarkAdmin\Actions;

/**
 * Class Link.
 */
abstract class Link extends Action
{
    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、modal、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'link';

    /**
     * 触发行为跳转链接
     *
     * @var string
     */
    public $href;

    /**
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @var string
     */
    public $target = '_self';

    /**
     * 获取跳转链接
     *
     * @return string
     */
    public function href()
    {
        return $this->href;
    }

    /**
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @return $this
     */
    public function target()
    {
        return $this->target;
    }
}
