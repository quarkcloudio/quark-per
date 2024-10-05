<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Map extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'mapField';

    /**
     * 默认值
     *
     * @var array
     */
    public $value = ['longitude' => '116.397724', 'latitude' => '39.903755'];

    /**
     * zoom
     *
     * @var number
     */
    public $zoom = 14;

    /**
     * 高德地图key
     *
     * @var string
     */
    public $mapKey = '788e08def03f95c670944fe2c78fa76f';

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['height' => 400, 'width' => '100%', 'marginTop' => '10px'];

    /**
     * zoom
     *
     * @param  string  $zoom
     * @return $this
     */
    public function zoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    /**
     * 高德地图key
     *
     * @param  string  $key
     * @return $this
     */
    public function mapKey($key)
    {
        $this->mapKey = $key;
        return $this;
    }

    /**
     * 地图宽度
     *
     * @param  string|number  $width
     * @return $this
     */
    public function width($width)
    {
        $this->style['width'] = $width;
        return $this;
    }

    /**
     * 地图高度
     *
     * @param  string|number  $height
     * @return $this
     */
    public function height($height)
    {
        $this->style['height'] = $height;
        return $this;
    }

    /**
     * 坐标位置
     *
     * @param  string|number  $longitude
     * @param  string|number  $latitude
     * @return $this
     */
    public function position($longitude,$latitude)
    {
        $position['longitude'] = $longitude;
        $position['latitude'] = $latitude;
        $this->value = $position;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'zoom' => $this->zoom,
            'mapKey' => $this->mapKey
        ], parent::jsonSerialize());
    }
}
