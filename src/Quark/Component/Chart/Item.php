<?php

namespace QuarkCloudIO\Quark\Component\Chart;

use QuarkCloudIO\Quark\Component\Element;

abstract class Item extends Element
{
    /**
     * 数据接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 设置图表宽度
     *
     * @var number
     */
    public $width = null;

    /**
     * 设置图表高度
     *
     * @var number
     */
    public $height = null;

    /**
     * 图表是否自适应容器宽高。当 autoFit 设置为 true 时，width 和 height 的设置将失效。
     *
     * @var bool
     */
    public $autoFit = true;

    /**
     * 画布的 padding 值，代表图表在上右下左的间距，可以为单个数字 16，或者数组 [16, 8, 16, 8] 代表四个方向，或者开启 auto，由底层自动计算间距。
     *
     * @var string|number|array
     */
    public $padding = 'auto';
    
    /**
     * 额外增加的 appendPadding 值，在 padding 的基础上，设置额外的 padding 数值，可以是单个数字 16，或者数组 [16, 8, 16, 8] 代表四个方向。
     *
     * @var number|array
     */
    public $appendPadding = null;

    /**
     * 设置图表渲染方式为 canvas 或 svg。
     *
     * @var string
     */
    public $renderer = 'canvas';

    /**
     * 是否对超出坐标系范围的 Geometry 进行剪切。
     *
     * @var bool
     */
    public $limitInPlot = false;

    /**
     * 指定具体语言，目前内置 'zh-CN' and 'en-US' 两个语言，你也可以使用 G2Plot.registerLocale 方法注册新的语言。语言包格式参考：src/locales/en_US.ts
     *
     * @var string
     */
    public $locale = 'zh-CN';

    /**
     * 数据
     *
     * @var array
     */
    public $data = [];

    /**
     * X轴字段
     *
     * @var string
     */
    public $xField = null;

    /**
     * y轴字段
     *
     * @var string
     */
    public $yField = null;

    /**
     * 通过 meta 可以全局化配置图表数据元信息，以字段为单位进行配置。在 meta 上的配置将同时影响所有组件的文本信息。传入以字段名为 key，MetaOption 为 value 的配置，同时设置多个字段的元信息。
     *
     * @var array
     */
    public $meta = [];

    /**
     * 初始化组件
     *
     * @return void
     */
    public function __construct($data = null) {
        $this->component = 'chart';
        $this->data = $data;

        return $this;
    }

    /**
     * 数据接口
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
     * 设置图表宽度
     *
     * @param  number  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * 设置图表高度
     *
     * @param  number  $height
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * 图表是否自适应容器宽高。当 autoFit 设置为 true 时，width 和 height 的设置将失效。
     *
     * @param  bool  $autoFit
     * @return $this
     */
    public function autoFit($autoFit)
    {
        $this->autoFit = $autoFit;
        return $this;
    }

    /**
     * 画布的 padding 值，代表图表在上右下左的间距，可以为单个数字 16，或者数组 [16, 8, 16, 8] 代表四个方向，或者开启 auto，由底层自动计算间距。
     *
     * @param  string|number|array  $padding
     * @return $this
     */
    public function padding($padding)
    {
        $this->padding = $padding;
        return $this;
    }

    /**
     * 额外增加的 appendPadding 值，在 padding 的基础上，设置额外的 padding 数值，可以是单个数字 16，或者数组 [16, 8, 16, 8] 代表四个方向。
     *
     * @param  string|array  $appendPadding
     * @return $this
     */
    public function appendPadding($appendPadding)
    {
        $this->appendPadding = $appendPadding;
        return $this;
    }

    /**
     * 设置图表渲染方式为 canvas 或 svg。
     *
     * @param  string  $renderer
     * @return $renderer
     */
    public function renderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * 是否对超出坐标系范围的 Geometry 进行剪切。
     *
     * @param  bool  $limitInPlot
     * @return $this
     */
    public function limitInPlot($limitInPlot)
    {
        $this->limitInPlot = $limitInPlot;
        return $this;
    }

    /**
     * 指定具体语言，目前内置 'zh-CN' and 'en-US' 两个语言，你也可以使用 G2Plot.registerLocale 方法注册新的语言。语言包格式参考：src/locales/en_US.ts
     *
     * @param  string  $locale
     * @return $this
     */
    public function locale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * 数据
     *
     * @param  array  $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * X轴字段
     *
     * @param  string  $xField
     * @return $this
     */
    public function xField($xField)
    {
        $this->xField = $xField;
        return $this;
    }

    /**
     * y轴字段
     *
     * @param  string  $yField
     * @return $this
     */
    public function yField($yField)
    {
        $this->yField = $yField;
        return $this;
    }
    
    /**
     * 通过 meta 可以全局化配置图表数据元信息，以字段为单位进行配置。在 meta 上的配置将同时影响所有组件的文本信息。传入以字段名为 key，MetaOption 为 value 的配置，同时设置多个字段的元信息。
     *
     * @param  array  $meta
     * @return $this
     */
    public function meta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key();

        return array_merge([
            'api' => $this->api,
            'width' => $this->width,
            'height' => $this->height,
            'autoFit' => $this->autoFit,
            'padding' => $this->padding,
            'appendPadding' => $this->appendPadding,
            'renderer' => $this->renderer,
            'limitInPlot' => $this->limitInPlot,
            'locale' => $this->locale,
            'xField' => $this->xField,
            'yField' => $this->yField,
            'data' => $this->data,
            'meta' => $this->meta,
        ], parent::jsonSerialize());
    }
}