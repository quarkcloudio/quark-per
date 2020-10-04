<?php


namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Map extends Item
{
    public  $type,$zoom,$key;

    function __construct($name,$label = '') {
        $this->component = 'map';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->type = 'text';
        $this->zoom = 14;
        $this->key = '788e08def03f95c670944fe2c78fa76f';
        $position['longitude'] = '116.397724';
        $position['latitude'] = '39.903755';
        $this->value = $position;
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
