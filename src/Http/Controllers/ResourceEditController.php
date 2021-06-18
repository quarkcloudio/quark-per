<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\QuarkAdmin\Http\Requests\ResourceEditRequest;

class ResourceEditController extends Controller
{
    /**
     * 编辑页
     *
     * @param  ResourceEditRequest  $request
     * @return array
     */
    public function handle(ResourceEditRequest $request)
    {
        return $request->newResource()->setLayoutContent(
            $this->buildComponent(
                $request,
                $request->newResourceWith($request->fillData())->toArray($request)
            )
        );
    }

    /**
     * 创建组件
     *
     * @param  ResourceEditRequest  $request
     * @param  array  $data
     * @return array
     */
    public function buildComponent($request, $data)
    {
        // 表单
        $form = Form::api($request->newResource()->updateApi())
        ->style(['marginTop' => '30px'])
        ->items($request->newResource()->updateFields($request))
        ->actions($request->newResource()->formActions())
        ->initialValues($request->newResource()->beforeEditing($request, $data));

        return Card::title('编辑' . $request->newResource()->title())
        ->headerBordered()
        ->extra($request->newResource()->formExtra())
        ->body($form);
    }
}