<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceIndexRequest;
use QuarkCMS\Quark\Facades\Table;

class ResourceIndexController extends Controller
{
    /**
     * 列表页
     *
     * @param  ResourceIndexRequest  $request
     * @return array
     */
    public function handle(ResourceIndexRequest $request)
    {
        $resource = $request->resource();
        $component = $this->buildComponent(
                    $request,
                    $resource,
                    $request->newResource()::collection($request->indexQuery())
                );

        return $request->newResource()->setLayoutContent($component);
    }
    
    /**
     * 创建组件
     *
     * @param  ResourceIndexRequest  $request
     * @param  object  $resource
     * @param  object  $data
     * @return array
     */
    public function buildComponent($request, $resource , $data)
    {
        $table = Table::key('table')
        ->title($request->newResource()->title() . '列表')
        ->toolBar($request->newResource()->toolBar($request))
        ->columns($request->newResource()->columns($request))
        ->batchActions($request->newResource()->tableAlertActions($request))
        ->searches($request->newResource()->indexSearches($request));

        // 列表页展示前回调
        $data = $request->newResource()->beforeIndexShowing($request, $data);

        if($resource::pagination()) {
            $table = $table->pagination(
                $data->currentPage(),
                $data->perPage(),
                $data->total()
            )->datasource($data->items());
        } else {
            $table = $table->datasource($data);
        }

        return $table;
    }
}