<?php

namespace QuarkCMS\QuarkAdmin\Resources;

use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Component\Login\Login as LoginComponent;

/**
 * Class Login Resource.
 */
class Login extends LoginComponent
{
    /**
     * 登录接口
     *
     * @var string
     */
    public $api = 'admin/login';

    /**
     * 登录后跳转地址
     *
     * @var string
     */
    public $redirect = '/index?api=app/dashboard/index';

    /**
     * 验证码链接
     *
     * @var string|bool
     */
    public $captchaUrl = 'api/admin/captcha';

    /**
     * 资源结构
     *
     * @param  void
     * @return array
     */
    public function resource()
    {
        return Page::title($this->title)->body($this->jsonSerialize());
    }
}
