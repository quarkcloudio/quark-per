<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
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
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function indexResource(Request $request)
    {
        $model = static::newModel();

        $fields = $this->fields($request);

        $table = Table::key('table')
        ->title($this->title)
        ->toolBar(false)
        ->columns([])
        ->batchActions([]);

        return $this->setLayoutContent($table);
    }
}
