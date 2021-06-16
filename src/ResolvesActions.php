<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\Quark\Facades\Space;

trait ResolvesActions
{
    /**
     * 列表行为
     *
     * @param  Request  $request
     * @return array
     */
    public function indexActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnIndex()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return Space::body($actions ?? []);
    }

    /**
     * 表格行内行为
     *
     * @param  Request  $request
     * @return array
     */
    public function tableRowActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnTableRow()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 表格多选弹出层行为
     *
     * @param  Request  $request
     * @return array
     */
    public function tableAlertActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnTableAlert()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 创建行为组件
     *
     * @param  object  $item
     * @return array
     */
    protected function buildAction($item)
    {
        $builder = Action::make($item->name())
        ->reload('table')
        ->api($item->api())
        ->actionType($item->actionType())
        ->showStyle($item->showStyle())
        ->size($item->size());

        if($item->icon()) {
            $builder->icon($item->icon());
        }

        if($item->actionType() === 'link') {
            $builder->link($item->href(), $item->target());
        }

        if($item->confirmTitle) {
            $builder->withConfirm($item->confirmTitle, $item->confirmText, $item->confirmType);
        }

        return $builder->render();
    }

    /**
     * 定义行为
     *
     * @param  Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
