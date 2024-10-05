<?php

namespace QuarkCloudIO\Quark\Component\Grid;

use QuarkCloudIO\Quark\Component\Element;

class Col extends Element
{
    /**
     * 布局属性
     *
     * @var string | number
     */
    public $flex = null;

    /**
     * 栅格左侧的间隔格数，间隔内不可以有栅格
     *
     * @var number
     */
    public $offset = 0;

    /**
     * 栅格顺序
     *
     * @var number
     */
    public $order = 0;

    /**
     * 栅格向左移动格数
     *
     * @var number
     */
    public $pull = 0;

    /**
     * 栅格向右移动格数
     *
     * @var number
     */
    public $push = 0;

    /**
     * 栅格占位格数，为 0 时相当于 display: none
     *
     * @var number
     */
    public $span = null;

    /**
     * 屏幕 < 576px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $xs = null;

    /**
     * 屏幕 ≥ 576px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $sm = null;

    /**
     * 屏幕 ≥ 768px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $md = null;

    /**
     * 屏幕 ≥ 992px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $lg = null;

    /**
     * 屏幕 ≥ 1200px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $xl = null;

    /**
     * 屏幕 ≥ 1600px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @var number | object
     */
    public $xxl = null;

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
        $this->component = 'col';

        return $this;
    }

    /**
     * 布局属性
     *
     * @param  string | number  $flex
     * @return $this
     */
    public function flex($flex)
    {
        $this->flex = $flex;

        return $this;
    }

    /**
     * 栅格左侧的间隔格数，间隔内不可以有栅格
     *
     * @param  number  $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * 栅格顺序
     *
     * @param  number  $order
     * @return $this
     */
    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * 栅格向左移动格数
     *
     * @param  number  $pull
     * @return $this
     */
    public function pull($pull)
    {
        $this->pull = $pull;

        return $this;
    }

    /**
     * 栅格向右移动格数
     *
     * @param  number  $push
     * @return $this
     */
    public function push($push)
    {
        $this->push = $push;

        return $this;
    }

    /**
     * 栅格占位格数，为 0 时相当于 display: none
     *
     * @param  number  $span
     * @return $this
     */
    public function span($span)
    {
        $this->span = $span;

        return $this;
    }

    /**
     * 屏幕 < 576px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $xs
     * @return $this
     */
    public function xs($xs)
    {
        $this->xs = $xs;

        return $this;
    }

    /**
     * 屏幕 ≥ 576px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $sm
     * @return $this
     */
    public function sm($sm)
    {
        $this->sm = $sm;

        return $this;
    }

    /**
     * 屏幕 ≥ 768px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $md
     * @return $this
     */
    public function md($md)
    {
        $this->md = $md;

        return $this;
    }

    /**
     * 屏幕 ≥ 992px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $lg
     * @return $this
     */
    public function lg($lg)
    {
        $this->lg = $lg;

        return $this;
    }

    /**
     * 屏幕 ≥ 1200px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $xl
     * @return $this
     */
    public function xl($xl)
    {
        $this->xl = $xl;

        return $this;
    }

    /**
     * 屏幕 ≥ 1600px 响应式栅格，可为栅格数或一个包含其他属性的对象
     *
     * @param  number | object  $xxl
     * @return $this
     */
    public function xxl($xxl)
    {
        $this->xxl = $xxl;

        return $this;
    }

    /**
     * 内容
     *
     * @param  bool|string|number|array  $body
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
            $this->key(__CLASS__, true);
        }

        return array_merge([
            'flex' => $this->flex,
            'offset' => $this->offset,
            'order' => $this->order,
            'pull' => $this->pull,
            'push' => $this->push,
            'span' => $this->span,
            'xs' => $this->xs,
            'sm' => $this->sm,
            'md' => $this->md,
            'lg' => $this->lg,
            'xl' => $this->xl,
            'xxl' => $this->xxl,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
