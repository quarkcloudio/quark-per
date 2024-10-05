<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use QuarkCloudIO\QuarkAdmin\Http\Requests\DashboardRequest;

class DashboardController extends Controller
{
    /**
     * 仪表盘展示
     *
     * @param  DashboardRequest  $request
     * @return array
     */
    public function handle(DashboardRequest $request)
    {
        return $request->newResource()->render(
            $request,
            $request->newResource()->dashboardComponentRender($request)
        );
    }
}
