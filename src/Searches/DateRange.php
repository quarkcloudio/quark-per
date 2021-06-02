<?php

namespace QuarkCMS\QuarkAdmin\Searches;

/**
 * Class DateRange.
 */
abstract class DateRange extends Search
{
    /**
     * 控件类型
     *
     * @var string
     */
    public $type = 'date';

    /**
     * 操作符
     *
     * @var string
     */
    public $operator = 'between';
}