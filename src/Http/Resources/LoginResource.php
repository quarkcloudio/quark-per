<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Login;
use QuarkCMS\QuarkAdmin\Http\Resources\Resource;

class LoginResource extends Resource
{
    /**
     * 页面内容
     *
     * @param  Request  $request
     * @return Response
     */
    public function body()
    {
        $body = Login::make()
        ->api('admin/login')
        ->title('DeepblueCMS')
        ->description('description')
        ->captchaUrl('api/admin/captcha')
        ->redirect('index?api=admin/dashboard/index');

        return $body;
    }
}
