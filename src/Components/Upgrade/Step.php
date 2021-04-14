<?php

namespace QuarkCMS\QuarkAdmin\Components\Upgrade;

use QuarkCMS\QuarkAdmin\Element;

class Step extends Element
{
    /**
     * 更新步骤接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 提示信息
     *
     * @var string
     */
    public $tip = null;

    /**
     * 完成百分比
     *
     * @var number
     */
    public $percent = 0;

    /**
     * 初始化组件
     *
     * @param  string  $title
     * @param  string  $api
     * @return void
     */
    public function __construct($title = null,$api = null) {
        $this->component = 'upgradeStep';

        if(!empty($title)) {
            $this->title = $title;
        }

        if(!empty($api)) {
            $this->api = $api;
        }
    }

    /**
     * 完成百分比
     *
     * @param  number  $percent
     * @return $this
     */
    public function percent($percent)
    {
        $this->percent = $percent;
        return $this;
    }

    /**
     * 提示信息
     *
     * @param  string  $tip
     * @return $this
     */
    public function tip($tip)
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * 更新步骤接口
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this));

        return array_merge([
            'title' => $this->title,
            'tip' => $this->tip,
            'percent' => $this->percent,
            'api' => $this->api,
        ], parent::jsonSerialize());
    }
}
