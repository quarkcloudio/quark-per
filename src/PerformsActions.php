<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;

trait PerformsActions
{
    /**
     * 执行行为
     *
     * @param  Request  $request
     * @param  Builder  $model
     * @param  string  $uriKey
     * @param  object  $actions
     * @return array
     */
    public static function handleRequest(Request $request, $model, $uriKey, $actions)
    {
        $model = $model->whereIn('id', explode(',', $request->input('id')));

        foreach ($actions as $value) {
            if($uriKey === $value->uriKey()) {
                return $value->handle($request, [0 => $model]);
            }
        }
    }
}
