<?php

namespace QuarkCloudIO\QuarkAdmin\Metrics;

use QuarkCloudIO\Quark\Facades\Lists as ListsComponent;

/**
 * Class Lists.
 */
abstract class Lists extends Metric
{
    /**
     * toolBar
     *
     * @return array
     */
    protected function toolBar()
    {
        return [];
    }

    /**
     * metas
     *
     * @return array
     */
    protected function metas()
    {
        return [];
    }

    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @return Lists
     */
    public function result($value)
    {
        return ListsComponent::title($this->title)
        ->toolBar($this->toolBar())
        ->metas($this->metas())
        ->datasource($value)
        ->style(['paddingTop' => '6px','paddingBottom' => '12px']);
    }
}
