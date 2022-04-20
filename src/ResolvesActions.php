<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\Quark\Facades\Dropdown;
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
        $actions = [];

        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnIndex()) {
                $actions[] = $this->buildAction($value);
            }
        }

        // 是否携带导入行为
        if($request->resource()::$withImport) {
            $actions[] = $this->buildAction(new \App\Admin\Actions\Import);
        }

        return Space::body($actions);
    }

    /**
     * 表格行内行为
     *
     * @param  Request  $request
     * @return array
     */
    public function indexTableRowActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnIndexTableRow()) {
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
    public function indexTableAlertActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnIndexTableAlert()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 表单页行为
     *
     * @param  Request  $request
     * @return array
     */
    public function formActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnForm()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 表单页右上角自定义区域行为
     *
     * @param  Request  $request
     * @return array
     */
    public function formExtraActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnFormExtra()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 详情页行为
     *
     * @param  Request  $request
     * @return array
     */
    public function detailActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnDetail()) {
                $actions[] = $this->buildAction($value);
            }
        }

        return $actions ?? [];
    }

    /**
     * 详情页右上角自定义区域行为
     *
     * @param  Request  $request
     * @return array
     */
    public function detailExtraActions(Request $request)
    {
        foreach ($this->actions($request) as $key => $value) {
            if($value->shownOnDetailExtra()) {
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
        if($item->actionType() === 'dropdown') {
            $builder = Dropdown::make($item->name(), $item->overlay())
            ->overlayStyle($item->overlayStyle())
            ->placement($item->placement())
            ->trigger($item->trigger())
            ->arrow($item->arrow())
            ->type($item->type())
            ->size($item->size());

            if($item->icon()) {
                $builder->icon($item->icon());
            }

            return $builder;
        }

        $builder = Action::make($item->name())
        ->withLoading($item->withLoading())
        ->reload($item->reload())
        ->api($item->api())
        ->actionType($item->actionType())
        ->type($item->type())
        ->size($item->size());

        if($item->icon()) {
            $builder->icon($item->icon());
        }

        if($item->actionType() === 'js') {
            $builder->js($item->js());
        }

        if($item->actionType() === 'link') {
            $builder->link($item->href(), $item->target());
        }

        if($item->actionType() === 'modal') {
            $builder->modal(function($modal) use ($item) {
                return $modal->title($item->name())
                ->width($item->width())
                ->body($item->body())
                ->destroyOnClose()
                ->actions($item->actions());
            });
        }

        if($item->actionType() === 'drawer') {
            $builder->drawer(function($drawer) use ($item) {
                return $drawer->title($item->name())
                ->width($item->width())
                ->body($item->body())
                ->destroyOnClose()
                ->actions($item->actions());
            });
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
