<?php

namespace QuarkCloudIO\QuarkAdmin\Searches;

/**
 * Class DateTimeRange.
 */
abstract class DateTimeRange extends Search
{
    /**
     * 控件类型
     *
     * @var string
     */
    public $component = 'datetime';

    /**
     * 操作符
     *
     * @var string
     */
    public $operator = 'between';
}