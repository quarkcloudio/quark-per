<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Tabs;
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
        $items = $request->newResource()->fields($request);

        // 表单
        $form = Form::api(
            $request->newResource()->formApi($request)
        )
        ->actions(
            $request->newResource()->formActions($request)
        );

        switch ($items[0]->component) {
            case 'tabPane':
                $component = $form->style([
                    'marginTop' => '30px',
                    'backgroundColor' => '#fff',
                    'paddingBottom' => '20px'
                ])
                ->items(
                    Tabs::tabPanes($items)->tabBarExtraContent($request->newResource()->formExtra($request))
                )
                ->initialValues(
                    $request->newResource()->beforeFormShowing($request)
                );
                break;
            
            default:
                $form = $form->style([
                    'marginTop' => '30px'
                ])
                ->items($items)
                ->initialValues(
                    $request->newResource()->beforeFormShowing($request)
                );

                $component = Card::title($request->newResource()->formTitle($request))
                ->headerBordered()
                ->extra($request->newResource()->formExtra($request))
                ->body($form);
                break;
        }

        return $component;
    }
}