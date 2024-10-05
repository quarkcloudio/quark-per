<?php

namespace QuarkCloudIO\Quark\Component\Divider;

use QuarkCloudIO\Quark\Component\Element;
use Closure;

class Divider extends Element
{
    /**
     * 是否虚线
     *
     * @var bool
     */
    public $dashed = false;

    /**
     * 分割线标题的位置,left | right | center
     *
     * @var string
     */
    public $orientation = 'center';

    /**
     * 文字是否显示为普通正文样式
     *
     * @var bool
     */
    public $plain = false;

    /**
     * 水平还是垂直类型,horizontal | vertical
     *
     * @var string
     */
    public $type = 'horizontal';

    /**
     * 内容
     *
     * @var string | array
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  \Closure|array  $body
     * @return void
     */
    public function __construct($body = [])
    {
        $this->component = 'divider';
        $this->body = $body;

        return $this;
    }

    /**
     * 是否虚线
     *
     * @param  bool  $dashed
     * @return $this
     */
    public function dashed($dashed = true)
    {
        $this->dashed = $dashed;

        return $this;
    }

    /**
     * 间距方向
     *
     * @param  string  $orientation
     * @return $this
     */
    public function orientation($orientation)
    {
        if(!in_array($orientation,['left', 'right', 'center'])) {
            throw new \Exception("Argument must be in 'left', 'right', 'center' !");
        }

        $this->orientation = $orientation;

        return $this;
    }

    /**
     * 文字是否显示为普通正文样式
     *
     * @param  bool  $plain
     * @return $this
     */
    public function plain($plain = true)
    {
        $this->plain = $plain;

        return $this;
    }

    /**
     * 水平还是垂直类型,horizontal | vertical
     *
     * @param  string  $type
     * @return $this
     */
    public function type($type)
    {
        if(!in_array($type,['vertical', 'horizontal'])) {
            throw new \Exception("Argument must be in 'vertical', 'horizontal'!");
        }

        $this->type = $type;

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
            $this->key(json_encode($this), true);
        }

        return array_merge([
            'dashed' => $this->dashed,
            'orientation' => $this->orientation,
            'plain' => $this->plain,
            'type' => $this->type,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
