<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Map extends Item
{
    /**
     * type
     *
     * @var string
     */
    public $type = 'text';

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
     * 初始化组件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name,$label = '') {
        $this->component = 'map';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $position['longitude'] = '116.397724';
        $position['latitude'] = '39.903755';
        $this->value = $position;

        $style['height'] = '400px';
        $style['width'] = '100%';
        $style['marginTop'] = '10px';

        $this->style = $style;
    }

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
            'type' => $this->type,
            'zoom' => $this->zoom,
            'mapKey' => $this->mapKey
        ], parent::jsonSerialize());
    }
}
