<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Search;

trait ResolvesSearches
{
    /**
     * 列表页搜索表单
     *
     * @param  Request  $request
     * @return array
     */
    public function indexSearches(Request $request)
    {
        $search = Search::make();

        // 搜索栏是否携带导出功能
        if($request->resource()::$withExport) {
            $search = $search->showExportButton()->exportApi('admin/'.request()->route('resource').'/export');
        }
        
        foreach ($this->searches($request) as $key => $value) {
            $item = $search->item($value->column, $value->name)->operator($value->operator)->api($value->api);

            // 根据控件类型进行回调，生成表单的字段项
            call_user_func_array([$item, $value->component], [$value->options($request)]);
        }

        return $search;
    }

    /**
     * 定义搜索表单
     *
     * @param  Request  $request
     * @return array
     */
    public function searches(Request $request)
    {
        return [];
    }
}
