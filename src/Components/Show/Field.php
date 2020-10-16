<?php

namespace QuarkCMS\QuarkAdmin\Components\Show;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Element;

class Field extends Element
{
    /**
     * label 标签的文本
     *
     * @var string
     */
    public $label = null;

    /**
     * 字段名，支持数组
     *
     * @var string|array
     */
    public $name = null;

    /**
     * 是否为图片
     *
     * @var bool|array
     */
    public $image = false;

    /**
     * 是否为链接
     *
     * @var bool|array
     */
    public $link = false;

    /**
     * 设置保存值。
     *
     * @var bool|string|number|array
     */
    public $value = null;

    /**
     * 设置默认值。
     *
     * @var bool|string|number|array
     */
    public $defaultValue = null;

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @var string
     */
    public $extra = null;

    /**
     * 额外的提示信息。
     *
     * @var string
     */
    public $help = null;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    public function __construct($name,$label = '') {
        $this->component = 'text';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @param  string $extra
     * @return $this
     */
    public function extra($extra = '')
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * 配合 help 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @param  bool $help
     * @return $this
     */
    public function help($help = '')
    {
        $this->help = $help;
        return $this;
    }

    /**
     * label 标签的文本
     *
     * @param  string $label
     * @return $this
     */
    public function label($label = '')
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 字段名，支持数组
     *
     * @param  string $name
     * @return $this
     */
    public function name($name = '')
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 设置保存值。
     *
     * @param  array|string
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置默认值。
     *
     * @param  array|string
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * 图片字段
     *
     * @param  number $width
     * @param  number $height
     * @return $this
     */
    public function image($width = 100,$height = 100)
    {
        $this->component = 'image';

        $image['width'] = $width;
        $image['height'] = $height;
        $this->image = $image;
        return $this;
    }

    /**
     * 设置跳转链接
     *
     * @param  string  $href
     * @param  string  $href
     * @return $this
     */
    public function link($href = null, $target = '_self')
    {
        $this->link['href'] = $href;
        $this->link['target'] = $target;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->name.$this->label);

        return array_merge([
            'label' => $this->label,
            'name' => $this->name,
            'image' => $this->image,
            'link' => $this->link,
            'help' => $this->help,
            'extra' => $this->extra,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue
        ], parent::jsonSerialize());
    }
}
