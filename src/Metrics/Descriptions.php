<?php

namespace QuarkCMS\QuarkAdmin\Metrics;

use QuarkCMS\Quark\Facades\Descriptions as DescriptionsComponent;

/**
 * Class Descriptions.
 */
abstract class Descriptions extends Metric
{
    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @return Descriptions
     */
    public function result($value)
    {
        return DescriptionsComponent::title($this->title)->items($value);
    }
}
