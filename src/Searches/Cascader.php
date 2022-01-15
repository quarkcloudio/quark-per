<?php

namespace QuarkCMS\QuarkAdmin\Searches;

/**
 * Class Cascader.
 */
abstract class Cascader extends Search
{
    /**
     * 控件类型
     *
     * @var string
     */
    public $component = 'cascader';

    /**
     * 接口
     *
     * @var string
     */
    public $api = null;
}