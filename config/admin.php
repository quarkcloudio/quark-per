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
    'logo' => false,

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
    | Quark-admin captcha
    |--------------------------------------------------------------------------
    |
    | 登录验证码类型，tencent_captcha：腾讯云验证码，local：本地图形验证码
    |
    */
    'captcha_driver' => 'local',

    /*
    |--------------------------------------------------------------------------
    | tencent captcha config
    |--------------------------------------------------------------------------
    |
    | 腾讯云验证码配置
    |
    */
    'tencent_captcha' => [
        'appid' => env('appid'),
        'app_secret_key' => env('app_secret_key')
    ],

    /*
    |--------------------------------------------------------------------------
    | Quark-admin iconfontUrl
    |--------------------------------------------------------------------------
    |
    | 使用 IconFont 的图标配置
    |
    */
    'iconfont_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Quark-admin layout
    |--------------------------------------------------------------------------
    |
    | The layout of quark-admin
    |
    */
    'layout' => [

        // layout 的菜单模式,side：右侧导航，top：顶部导航
        'layout' => 'side',

        // layout 的内容模式,Fluid：定宽 1200px，Fixed：自适应
        'content_width' => 'Fixed',

        // 导航的主题，'light' | 'dark'
        'nav_theme' => 'dark',

        // 是否固定 header 到顶部
        'fixed_header' => true,

        // 是否固定导航
        'fix_siderbar' => false,

        // 使用 IconFont 的图标配置
        'iconfont_url' => config('admin.iconfont_url'),

        // 当前 layout 的语言设置，'zh-CN' | 'zh-TW' | 'en-US'
        'locale' => config('locale','zh-CN'),

        // 侧边菜单宽度
        'sider_width' => 208,

        // 控制菜单的收起和展开
        'collapsed' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | Quark-admin copyright
    |--------------------------------------------------------------------------
    |
    | 网站版权
    |
    */
    'copyright' => '2020 QuarkCMS',

    /*
    |--------------------------------------------------------------------------
    | Quark-admin friend links
    |--------------------------------------------------------------------------
    |
    | 友情链接
    |
    */
    'links' => [
        [
            'title' => '百度',
            'href' => 'https://www.baidu.com'
        ],
        [
            'title' => '淘宝',
            'href' => 'https://www.taobao.com'
        ],
        [
            'title' => '知乎',
            'href' => 'https://www.zhihu.com'
        ]
    ],

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

