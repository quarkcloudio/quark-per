<?php

namespace QuarkCloudIO\QuarkAdmin;

use QuarkCloudIO\QuarkAdmin\Layout;

/**
 * Class Admin.
 */
class Admin
{
    /**
     * Get the current Admin version.
     *
     * @return string
     */
    public static function version()
    {
        return 'v2.2.25';
    }

    /**
     * Get the current Admin info.
     *
     * @return string
     */
    public function info()
    {
        return [
            'version' => $this->version(),
            'name' => config('admin.name'),
            'logo' => config('admin.logo'),
            'description' => config('admin.description'),
            'login_type' => config('admin.login_type'),
            'captcha_driver' => config('admin.captcha_driver'),
            'tencent_captcha_appid' => config('admin.tencent_captcha.appid'),
            'iconfont_url' => config('admin.iconfont_url'),
            'copyright' => config('admin.copyright'),
            'links' => config('admin.links'),
        ];
    }

    /**
     * Get the current Admin layout.
     *
     * @return string
     */
    public function layout()
    {
        $layout = new Layout;

        $layout->title(config('admin.name'));
        $layout->logo(config('admin.logo'));
        $layout->headerActions(config('admin.layout.header_actions'));
        $layout->layout(config('admin.layout.layout'));
        $layout->splitMenus(config('admin.layout.split_menus'));
        $layout->headerTheme(config('admin.layout.header_theme'));
        $layout->contentWidth(config('admin.layout.content_width'));
        $layout->navTheme(config('admin.layout.nav_theme'));
        $layout->primaryColor(config('admin.layout.primary_color'));
        $layout->fixedHeader(config('admin.layout.fixed_header'));
        $layout->fixSiderbar(config('admin.layout.fix_siderbar'));
        $layout->iconfontUrl(config('admin.layout.iconfont_url'));
        $layout->locale(config('admin.layout.locale'));
        $layout->siderWidth(config('admin.layout.sider_width'));

        return $layout;
    }

    /**
     * Dynamically proxy method calls.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        $getCalledClass = __NAMESPACE__.'\\'.ucwords($method);

        if(!class_exists($getCalledClass)) {
            throw new \Exception("Class {$method} does not exist.");
        }

        return new $getCalledClass(...$parameters);
    }
}
