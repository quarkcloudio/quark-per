<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Quark-admin App Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to display the name of the application within the UI
    | or in other locations. Of course, you're free to change the value.
    |
    */
    'name' => 'QaurkCMS',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo' => '/logo.png',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin description
    |--------------------------------------------------------------------------
    |
    | The description of login page.
    |
    */
    'description' => '信息丰富的世界里，唯一稀缺的就是人类的注意力',
    
    /*
    |--------------------------------------------------------------------------
    | Quark-admin layout
    |--------------------------------------------------------------------------
    |
    | layout 的菜单模式,side：右侧导航，top：顶部导航
    |
    */
    'layout' => 'side',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin contentWidth
    |--------------------------------------------------------------------------
    |
    | layout 的内容模式,Fluid：定宽 1200px，Fixed：自适应
    |
    */
    'content_width' => 'Fixed',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin nav theme
    |--------------------------------------------------------------------------
    |
    | 导航的主题，'light' | 'dark'
    |
    */
    'nav_theme' => 'dark',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin fixedHeader
    |--------------------------------------------------------------------------
    |
    | 是否固定 header 到顶部
    |
    */
    'fixed_header' => true,

    /*
    |--------------------------------------------------------------------------
    | Quark-admin fixSiderbar
    |--------------------------------------------------------------------------
    |
    | 是否固定导航
    |
    */
    'fix_siderbar' => false,

    /*
    |--------------------------------------------------------------------------
    | Quark-admin iconfontUrl
    |--------------------------------------------------------------------------
    |
    | 使用 IconFont 的图标配置
    |
    */
    'iconfont_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin locale
    |--------------------------------------------------------------------------
    |
    | 当前 layout 的语言设置，'zh-CN' | 'zh-TW' | 'en-US'
    |
    */
    'locale' => config('locale','zh-CN'),

    /*
    |--------------------------------------------------------------------------
    | Quark-admin siderWidth
    |--------------------------------------------------------------------------
    |
    | 侧边菜单宽度
    |
    */
    'sider_width' => 210,

    /*
    |--------------------------------------------------------------------------
    | Quark-admin collapsed
    |--------------------------------------------------------------------------
    |
    | 控制菜单的收起和展开
    |
    */
    'collapsed' => false,

    /*
    |--------------------------------------------------------------------------
    | Quark-admin auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all admin pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admins',
            ],
        ],

        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model'  => QuarkCMS\QuarkAdmin\Models\Admin::class,
            ],
        ],
    ],

];

