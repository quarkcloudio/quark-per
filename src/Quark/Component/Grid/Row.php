<?php

namespace QuarkCloudIO\Quark\Component\Grid;

use QuarkCloudIO\Quark\Component\Element;
use QuarkCloudIO\Quark\Component\Grid\Col;

class Row extends Element
{
    /**
     * 垂直对齐方式，top | middle | bottom
     *
     * @var string
     */
    public $align = 'top';

    /**
     * 栅格间隔，可以写成像素值或支持响应式的对象写法来设置水平间隔 { xs: 8, sm: 16, md: 24}。
     * 或者使用数组形式同时设置 [水平间距, 垂直间距]
     *
     * @var number | object | array
     */
    public $gutter = 0;

    /**
     * 水平排列方式，start | end | center | space-around | space-between
     *
     * @var string
     */
    public $justify = 'start';

    /**
     * 是否自动换行
     *
     * @var bool
     */
    public $wrap = true;

    /**
     * Col对象
     *
     * @var object
     */
    public $col;

    /**
     * 内容
     *
     * @var bool|string|number|array
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  void
     * @return object
     */
    public function __construct()
    {
        $this->component = 'row';
        $this->col = new Col;

        return $this;
    }

    /**
     * 垂直对齐方式
     *
     * @param  string  $align
     * @return $this
     */
    public function align($align)
    {
        if(!in_array($align,['top', 'middle', 'bottom'])) {
            throw new \Exception("Argument must be in 'top', 'middle', 'bottom'!");
        }

        $this->align = $align;

        return $this;
    }

    /**
     * 栅格间隔，可以写成像素值或支持响应式的对象写法来设置水平间隔 { xs: 8, sm: 16, md: 24}。
     * 或者使用数组形式同时设置 [水平间距, 垂直间距]
     *
     * @param  number | object | array  $gutter
     * @return $this
     */
    public function gutter($gutter)
    {
        $this->gutter = $gutter;

        return $this;
    }

    /**
     * 水平排列方式，start | end | center | space-around | space-between
     *
     * @param  string  $justify
     * @return $this
     */
    public function justify($justify)
    {
        if(!in_array($justify, ['start', 'end', 'center', 'space-around', 'space-between'])) {
            throw new \Exception("Argument must be in 'start', 'end', 'center', 'space-around', 'space-between'!");
        }

        $this->justify = $justify;

        return $this;
    }

    /**
     * 是否自动换行
     *
     * @param  bool  $wrap
     * @return $this
     */
    public function wrap($wrap = true)
    {
        $wrap ? $this->wrap = true : $this->wrap = false;

        return $this;
    }

    /**
     * 内容
     *
     * @param  bool|string|number|array|object  $body
     * @return $this
     */
    public function body($callback)
    {
        $this->body = gettype($callback) == 'object' ? $callback($this->col) : $callback;

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
            $this->key(__CLASS__, true);
        }

        return array_merge([
            'align' => $this->align,
            'gutter' => $this->gutter,
            'justify' => $this->justify,
            'wrap' => $this->wrap,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
