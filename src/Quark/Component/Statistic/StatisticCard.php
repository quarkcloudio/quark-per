<?php

namespace QuarkCloudIO\Quark\Component\Statistic;

use QuarkCloudIO\Quark\Component\Statistic\Statistic;
use QuarkCloudIO\Quark\Component\Element;

class StatisticCard extends Element
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 卡片右上角的操作区域
     *
     * @var string
     */
    public $extra = null;

    /**
     * 当卡片内容还在加载中时，可以用 loading 展示一个占位
     *
     * @var bool
     */
    public $loading = false;

    /**
     * 是否有边框
     *
     * @var bool
     */
    public $bordered = false;

    /**
     * 图表卡片
     *
     * @var array
     */
    public $chart = null;

    /**
     * 数值统计对象
     *
     * @var array
     */
    public $statisticObject = null;

    /**
     * 数值统计配置，布局默认为 vertical
     *
     * @var array
     */
    public $statistic = null;

    /**
     * 图表位置，相对于 statistic 的位置,left | right | bottom
     *
     * @var string
     */
    public $chartPlacement = null;

    /**
     * 额外指标展示
     *
     * @var array
     */
    public $footer = null;

    /**
     * 初始化组件
     *
     * @param  string  $title
     * @return void
     */
    public function __construct($title = null) {
        $this->component = 'statisticCard';
        $this->title = $title;
        $this->statisticObject = new Statistic;
    }

    /**
     * 卡片标题
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
     * 卡片右上角的操作区域
     *
     * @param  string  $extra
     * @return $this
     */
    public function extra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * 数值精度
     *
     * @param  bool  $loading
     * @return $this
     */
    public function loading($loading)
    {
        $loading ? $this->loading = true : $this->loading = false;

        return $this;
    }

    /**
     * 是否有边框
     *
     * @param  bool  $bordered
     * @return $this
     */
    public function bordered($bordered)
    {
        $bordered ? $this->bordered = true : $this->bordered = false;

        return $this;
    }

    /**
     * 图表卡片
     *
     * @param  array  $chart
     * @return $this
     */
    public function chart($chart)
    {
        $this->chart = $chart;

        return $this;
    }

    /**
     * 数值统计配置，布局默认为 vertical
     *
     * @param  array  $statistic
     * @return $this
     */
    public function statistic($statistic)
    {
        $this->statistic = $statistic;

        return $this;
    }

    /**
     * 图表位置，相对于 statistic 的位置,left | right | bottom
     *
     * @param  string  $chartPlacement
     * @return $this
     */
    public function chartPlacement($chartPlacement)
    {
        if(!in_array($chartPlacement,['left', 'right', 'bottom'])) {
            throw new \Exception("Argument must be in 'left', 'right', 'bottom'!");
        }

        $this->chartPlacement = $chartPlacement;

        return $this;
    }

    /**
     * 额外指标展示
     *
     * @param  array  $footer
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;

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
            $this->key(json_encode($this), true);
        }

        return array_merge([
            'title' => $this->title,
            'extra' => $this->extra,
            'loading' => $this->loading,
            'bordered' => $this->bordered,
            'chart' => $this->chart,
            'statistic' => $this->statistic,
            'chartPlacement' => $this->chartPlacement,
            'footer' => $this->footer,
        ], parent::jsonSerialize());
    }
}