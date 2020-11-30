<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin App Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to display the name of the application within the UI
    | or in other locations. Of course, you're free to change the value.
    |
    */
    'name' => 'QuarkAdmin',

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin logo
    |--------------------------------------------------------------------------
    |
    | The logo of all admin pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Admin logo">'.
    |
    */
    'logo' => false,

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin description
    |--------------------------------------------------------------------------
    |
    | The description of login page.
    |
    */
    'description' => '信息丰富的世界里，唯一稀缺的就是人类的注意力',

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin captcha:todo
    |--------------------------------------------------------------------------
    |
    | 登录验证码类型，tencent_captcha：腾讯云验证码，local：本地图形验证码
    |
    */
    'captcha_driver' => 'local',

    /*
    |--------------------------------------------------------------------------
    | tencent captcha config:todo
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
    | login type
    |--------------------------------------------------------------------------
    |
    | 登录方式['username']，暂时只支持username
    |
    */
    'login_type' => ['username'],

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin iconfontUrl
    |--------------------------------------------------------------------------
    |
    | 使用 IconFont 的图标配置
    |
    */
    'iconfont_url' => '//at.alicdn.com/t/font_1615691_3pgkh5uyob.js',

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin layout
    |--------------------------------------------------------------------------
    |
    | The layout of QuarkAdmin
    |
    */
    'layout' => [

        // layout 的左上角 的 title
        'title' => config('admin.name'),

        // layout 的左上角 的 logo
        'logo' => config('admin.logo'),

        // layout 的头部行为
        'header_actions' => [
            [
                'component' => 'icon',
                'icon' => 'icon-question-circle',
                'tooltip' => '使用文档',
                'href' => 'http://www.quarkcms.com/',
                'target' => '_blank',
                'style' => ['color' => '#000']
            ],
            // [
            //     'component' => 'a',
            //     'title' => '使用文档',
            //     'href' => 'http://www.ixiaoquan.com',
            //     'target' => '_blank'
            // ]
        ],

        // layout 的菜单模式,side：右侧导航，top：顶部导航，mix：混合模式
        'layout' => 'side',

        // layout 的菜单模式为mix时，是否自动分割菜单
        'split_menus' => false,

        // layout 的菜单模式为mix时，顶部主题 'dark' | 'light'
        'header_theme' => 'dark',

        // layout 的内容模式,Fluid：定宽 1200px，Fixed：自适应
        'content_width' => 'Fluid',

        // 导航的主题，'light' | 'dark'
        'nav_theme' => 'dark',

        // 主题色
        'primary_color' => '#1890ff',

        // 是否固定 header 到顶部
        'fixed_header' => true,

        // 是否固定导航
        'fix_siderbar' => true,

        // 使用 IconFont 的图标配置
        'iconfont_url' => config('admin.iconfont_url'),

        // 当前 layout 的语言设置，'zh-CN' | 'zh-TW' | 'en-US'
        'locale' => config('locale','zh-CN'),

        // 侧边菜单宽度
        'sider_width' => 208
    ],

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin copyright
    |--------------------------------------------------------------------------
    |
    | 网站版权
    |
    */
    'copyright' => '2020 QuarkCMS',

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin friend links
    |--------------------------------------------------------------------------
    |
    | 友情链接
    |
    */
    'links' => [
        [
            'title' => 'Quark',
            'href' => 'http://www.quarkcms.com/'
        ],
        [
            'title' => '爱小圈',
            'href' => 'http://www.ixiaoquan.com'
        ],
        [
            'title' => 'Github',
            'href' => 'https://github.com/quarkcms'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | QuarkAdmin auth setting
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

