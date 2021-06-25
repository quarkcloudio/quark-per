<?php

namespace QuarkCMS\QuarkAdmin\Actions;

/**
 * Class Dialog.
 */
abstract class Dialog extends Action
{
    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'dialog';
}
