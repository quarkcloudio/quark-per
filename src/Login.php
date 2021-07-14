<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\Login as LoginFacade;

/**
 * 登录资源类
 */
class Login
{
    /**
     * 资源对象
     *
     * @param  void
     * @return array
     */
    public function resource()
    {
        $login = LoginFacade::api(config('admin.api','admin/login'))
        ->title(config('admin.name','QuarkAdmin'))
        ->description(config('admin.description','信息丰富的世界里，唯一稀缺的就是人类的注意力'))
        ->redirect(config('admin.redirect','/index?api=admin/dashboard/index'))
        ->captchaUrl(config('admin.captchaUrl','/api/admin/captcha'))
        ->links(config('admin.links',[]))
        ->copyright(config('admin.copyright'));

        return Page::title(config('admin.name','QuarkAdmin'))->body($login);
    }
}
