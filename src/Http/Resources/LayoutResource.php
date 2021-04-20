<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\QuarkAdmin\Http\Resources\Resource;

class LayoutResource extends Resource
{
    /**
     * 页面内容
     *
     * @param  Request  $request
     * @return Response
     */
    public function body()
    {
        $body = Layout::make()->title('DeepblueCMS');

        return $body;
    }
}
