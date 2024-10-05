<?php

namespace QuarkCloudIO\Quark\Component\Space;

use QuarkCloudIO\Quark\Component\Element;
use Closure;

class Space extends Element
{
    /**
     * 对齐方式
     *
     * @var string
     */
    public $align;

    /**
     * 间距方向
     *
     * @var string
     */
    public $direction;

    /**
     * 间距大小
     *
     * @var string
     */
    public $size;

    /**
     * 设置拆分
     *
     * @var array
     */
    public $split;

    /**
     * 是否自动换行，仅在 horizontal 时有效
     *
     * @var bool
     */
    public $wrap = false;

    /**
     * 内容
     *
     * @var string | array
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $body
     * @return void
     */
    public function __construct($size = 'small', $body = [])
    {
        $this->component = 'space';
        $this->size = $size;
        $this->body = $body;

        return $this;
    }

    /**
     * 对齐方式
     *
     * @param  string  $align
     * @return $this
     */
    public function align($align)
    {
        $this->align = $align;

        return $this;
    }

    /**
     * 间距方向
     *
     * @param  string  $direction
     * @return $this
     */
    public function direction($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * 间距大小
     *
     * @param  string  $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * 拆分卡片的方向,vertical | horizontal
     *
     * @param  string  $split
     * @return $this
     */
    public function split($split)
    {
        if(!in_array($split,['vertical', 'horizontal'])) {
            throw new \Exception("Argument must be in 'vertical', 'horizontal'!");
        }

        $this->split = $split;

        return $this;
    }

    /**
     * 是否自动换行，仅在 horizontal 时有效
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
     * 容器控件里面的内容
     *
     * @param  string|array  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

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
            $this->key($this->align.$this->direction.$this->size.$this->split.$this->wrap, true);
        }

        return array_merge([
            'align' => $this->align,
            'direction' => $this->direction,
            'size' => $this->size,
            'split' => $this->split,
            'wrap' => $this->wrap,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
