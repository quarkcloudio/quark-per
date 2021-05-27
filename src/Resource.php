<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\Quark\Facades\Table;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;

    /**
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function indexResource()
    {
        $table = Table::key('table')->title($this->title)
        ->toolBar(false)
        ->columns([])
        ->batchActions([]);

        return $this->setLayoutContent($table);
    }
}
