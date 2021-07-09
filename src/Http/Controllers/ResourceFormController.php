<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\QuarkAdmin\Http\Requests\ResourceCreateRequest;

class ResourceFormController extends Controller
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
        $form = Form::api($request->newResource()->formApi($request))
        ->style(['marginTop' => '30px'])
        ->items($request->newResource()->fields($request))
        ->actions($request->newResource()->formActions($request))
        ->initialValues($request->newResource()->beforeFormShowing($request));

        return Card::title($request->newResource()->formTitle($request))
        ->headerBordered()
        ->extra($request->newResource()->formExtra($request))
        ->body($form);
    }
}