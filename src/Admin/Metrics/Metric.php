<?php

namespace QuarkCloudIO\QuarkAdmin\Metrics;

/**
 * Class Metric.
 */
abstract class Metric
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 卡片占的栅格数
     *
     * @var number
     */
    public $col = 6;
}
