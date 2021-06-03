<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Action;

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
                $actions[] = Action::make($value->name);
            }
        }

        return $actions;
    }

    /**
     * 详情行为
     *
     * @param  Request  $request
     * @return array
     */
    public function detailActions(NovaRequest $request)
    {

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
