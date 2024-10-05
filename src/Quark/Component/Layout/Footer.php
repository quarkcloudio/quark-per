<?php

namespace QuarkCloudIO\Quark\Component\Layout;

use QuarkCloudIO\Quark\Component\Element;

class Footer extends Element
{
    /**
     * 版权信息
     *
     * @var string
     */
    public $copyright;

    /**
     * 超链接
     *
     * @var string
     */
    public $links = [];

    /**
     * 初始化容器
     *
     * @param  string  $copyright
     * @param  array  $links
     * @return void
     */
    public function __construct($copyright = '', $links = [])
    {
        $this->component = 'footer';
        
        $this->copyright = $copyright;
        $this->links = $links;

        return $this;
    }

    /**
     * 版权信息
     *
     * @param  string  $copyright
     * @return $this
     */
    public function copyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * 超链接
     *
     * @param  string  $links
     * @return $this
     */
    public function links($links)
    {
        $this->links = $links;

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
            $this->key(__CLASS__.$this->copyright.json_encode($this->links), true);
        }

        return array_merge([
            'copyright' => $this->copyright,
            'links' => $this->links
        ], parent::jsonSerialize());
    }
}
