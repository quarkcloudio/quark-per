<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceImportRequest;

class ResourceImportController extends Controller
{
    /**
     * 导入数据
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function handle(ResourceImportRequest $request)
    {
        return $request->handleRequest();
    }

    /**
     * 导入数据模板
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function template(ResourceImportRequest $request)
    {
        // 列表页展示前回调
        $resource = $request->newResource();

        $fields = $request->newResource()->importFields($request);

        $exportTitles = [];

        foreach ($fields as $fieldKey => $fieldValue) {
            $exportTitles[] = $fieldValue->label;
        }

        return export('template', $exportTitles, []);
    }

    /**
     * 下载导入失败的数据
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function downloadFailed(ResourceImportRequest $request)
    {
        $failedFileId = $request->input('failedFileId');
        
        $failed = cache('failedFileId'.$failedFileId);

        if(empty($failed)) {
            return error('数据已过期！');
        }

        return export('failedData', $failed['failedHead'], $failed['failedData']);
    }
}