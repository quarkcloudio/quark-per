<?php


namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Map extends Item
{
    public  $type,$zoom,$key;

    function __construct() {
        $this->component = 'map';
        $this->type = 'text';
        $this->zoom = 14;
        $this->key = '788e08def03f95c670944fe2c78fa76f';
        $position['longitude'] = '116.397724';
        $position['latitude'] = '39.903755';
        $this->value = $position;
    }

    /**
     * 创建组件
     *
     * @param  string $name
     * @param  string $label
     * @return object
     */
    static function make($name,$label = '')
    {
        $self = new self();

        $self->name = $name;
        if(empty($label)) {
            $self->label = $name;
        } else {
            $self->label = $label;
        }

        $self->placeholder = '请输入'.$labelName;

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }
    
    public function zoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function key($key)
    {
        $this->key = $key;
        return $this;
    }

    public function position($longitude,$latitude)
    {
        $position['longitude'] = $longitude;
        $position['latitude'] = $latitude;
        $this->value = $position;
        return $this;
    }
}
