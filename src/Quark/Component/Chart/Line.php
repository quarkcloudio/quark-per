<?php

namespace QuarkCloudIO\Quark\Component\Chart;

use QuarkCloudIO\Quark\Component\Element;

class Line extends Item
{
    /**
     * 是否平滑
     *
     * @var bool
     */
    public $smooth = true;

    /**
     * 分组字段。用于同时看一个维度中不同情况的指标需求。比如：我们看不同大区最近 30 天的销售额趋势情况，那么这里的大区字段就是 seriesField。
     *
     * @var string
     */
    public $seriesField = "";

    /**
     * 初始化组件
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data = null) {
        $this->component = 'line';
        $this->data = $data;

        return $this;
    }

    /**
     * 是否平滑
     *
     * @param  bool  $smooth
     * @return $this
     */
    public function smooth($smooth)
    {
        $this->smooth = $smooth;
        return $this;
    }
    
    /**
     * 分组字段。用于同时看一个维度中不同情况的指标需求。比如：我们看不同大区最近 30 天的销售额趋势情况，那么这里的大区字段就是 seriesField。
     *
     * @param  string  $seriesField
     * @return $this
     */
    public function seriesField($seriesField)
    {
        $this->seriesField = $seriesField;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        $this->key();

        return array_merge([
            'smooth' => $this->smooth,
            'seriesField' => $this->seriesField
        ], parent::jsonSerialize());
    }
}