<?php

namespace QuarkCloudIO\Quark\Component\Login;

use QuarkCloudIO\Quark\Component\Element;

class Login extends Element
{
    /**
     * 登录接口
     *
     * @var string
     */
    public $api = '';

    /**
     * 登录后跳转地址
     *
     * @var string
     */
    public $redirect = '';

    /**
     * 标题
     *
     * @var string
     */
    public $title = '';

    /**
     * 描述
     *
     * @var string
     */
    public $description = '';

    /**
     * 验证码链接
     *
     * @var string|bool
     */
    public $captchaUrl = false;

    /**
     * 页脚版权信息
     *
     * @var string|bool
     */
    public $copyright = false;

    /**
     * 页脚友情链接
     *
     * @var array
     */
    public $links = [];

    /**
     * 初始化容器
     *
     * @param  void
     * @return object
     */
    public function __construct()
    {
        $this->component = 'login';

        return $this;
    }

    /**
     * 设置登录接口
     *
     * @param  bool  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * 登录成功后跳转地址
     *
     * @param  string  $redirect
     * @return $this
     */
    public function redirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * 标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * 描述
     *
     * @param  string  $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * 验证码链接
     *
     * @param  string  $url
     * @return $this
     */
    public function captchaUrl($url)
    {
        $this->captchaUrl = $url;

        return $this;
    }

    /**
     * 页脚版权信息
     *
     * @param  string  $copyright
     * @return $this
     */
    public function copyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * 页脚友情链接
     *
     * @param  string  $links
     * @return $this
     */
    public function links($links)
    {
        $this->links = $links;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->api, true);
        }

        return array_merge([
            'api' => $this->api,
            'redirect' => $this->redirect,
            'title' => $this->title,
            'description' => $this->description,
            'captchaUrl' => $this->captchaUrl,
            'copyright' => $this->copyright,
            'links' => $this->links
        ], parent::jsonSerialize());
    }
}
