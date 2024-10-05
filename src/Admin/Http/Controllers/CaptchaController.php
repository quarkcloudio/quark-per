<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

class CaptchaController extends Controller
{
    /**
     * 图形验证码
     * @param  void
     * @return string
     */
    public function captcha()
    {
        return captcha();
    }
}