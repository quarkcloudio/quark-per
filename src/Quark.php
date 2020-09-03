<?php

namespace QuarkCMS\QuarkAdmin;

/**
 * Class Quark.
 */
class Quark
{
    /**
     * Get the current quark version.
     *
     * @return string
     */
    public function version()
    {
        return '1.0.0';
    }

    /**
     * Get the current quark info.
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
            'captcha_driver' => config('admin.captcha_driver'),
            'tencent_captcha_appid' => config('admin.tencent_captcha.appid'),
        ];
    }

    /**
     * Get the current quark layout.
     *
     * @return string
     */
    public function layout()
    {
        return config('admin.layout');
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

        return new $getCalledClass($parameters);
    }
}
