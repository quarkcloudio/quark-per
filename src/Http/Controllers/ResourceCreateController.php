<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\QuarkAdmin\Http\Requests\ResourceCreateRequest;

class ResourceCreateController extends Controller
{
    /**
     * 创建页
     *
     * @param  ResourceCreateRequest  $request
     * @return array
     */
    public function handle(ResourceCreateRequest $request)
    {
        return $request->newResource()->setLayoutContent($this->buildComponent($request));
    }
    
    /**
     * 创建组件
     *
     * @param  ResourceCreateRequest  $request
     * @return array
     */
    public function buildComponent($request)
    {
        // 表单
        $form = Form::api($request->newResource()->creationApi())
        ->style(['marginTop' => '30px'])
        ->items($request->newResource()->creationFields($request))
        ->actions($request->newResource()->formActions())
        ->initialValues($request->newResource()->beforeCreating($request));

        return Card::title($request->newResource()->formTitle())
        ->headerBordered()
        ->extra($request->newResource()->formExtra())
        ->body($form);
    }
}