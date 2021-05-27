<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\QuarkAdmin\Http\Resources\Resource;

class AdminResource extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Post';

    /**
     * The pagination per-page options configured for this resource.
     *
     * @return array
     */
    public static $perPageOptions = [50, 100, 150];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
        ];
    }
}