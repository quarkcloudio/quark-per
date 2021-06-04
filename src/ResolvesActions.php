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
                $actions[] = $this->actionBuilder($value);
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
                $actions[] = $this->actionBuilder($value);
            }
        }

        return Space::body($actions ?? []);
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
                $actions[] = $this->actionBuilder($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 行为创建器
     *
     * @param  object  $action
     * @return array
     */
    protected function actionBuilder($action)
    {
        $builder = Action::make($action->name())
        ->actionType($action->actionType())
        ->showStyle($action->showStyle())
        ->icon($action->icon());

        if($action->actionType === 'link') {
            $builder->link($action->href(), $action->target());
        }

        return $builder->render();
    }

    /**
     * 行为
     *
     * @param  Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
