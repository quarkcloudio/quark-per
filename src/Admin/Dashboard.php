<?php

namespace QuarkCloudIO\QuarkAdmin;

/**
 * Class Dashboard.
 */
abstract class Dashboard
{
    use Layout;

    /**
     * 页面标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 页面子标题
     *
     * @var string
     */
    public $subTitle = null;

    /**
     * 获取页面标题
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * 获取页面子标题
     *
     * @return string
     */
    public function subTitle()
    {
        return $this->subTitle;
    }

    /**
     * 卡片列表
     *
     * @return array
     */
    public function cards()
    {
        return [];
    }
}
