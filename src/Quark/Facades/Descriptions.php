<?php

namespace QuarkCloudIO\Quark\Facades;

use QuarkCloudIO\Quark\Facades\Facade;

class Descriptions extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'descriptions';
    }
}
