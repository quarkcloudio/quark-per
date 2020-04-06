<?php

return [

    'version' => env('APP_VERSION', 'v1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Quark-Admin name
    |--------------------------------------------------------------------------
    |
    | This value is the name of Quark-admin, This setting is displayed on the
    | login page.
    |
    */
    'name' => env('APP_NAME', 'Quark-Admin'),

    /*
    |--------------------------------------------------------------------------
    | Quark-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => env('ADMIN_ROUTE_PREFIX', 'api'),

        'namespace' => 'App\\Http\\Controllers\\Admin',

        'middleware' => ['admin'],
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

        // The URIs that should be excluded from authorization.
        'excepts' => [
            'api/admin/appInfo',
            'api/admin/login',
            'api/admin/logout',
            'api/admin/captcha',
        ],
    ],
];

