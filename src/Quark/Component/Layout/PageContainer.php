<?php

namespace QuarkCloudIO\Quark\Component\Layout;

use QuarkCloudIO\Quark\Component\Element;
use Closure;

class PageContainer extends Element
{
    /**
     * 内容区
     *
     * @var array
     */
    public $content;

    /**
     * 额外内容区，位于 content 的右侧
     *
     * @var array
     */
    public $extraContent;

    /**
     * tab 标题列表
     *
     * @var array
     */
    public $tabList;

    /**
     * 当前高亮的 tab 项
     *
     * @var string
     */
    public $tabActiveKey;

    /**
     * tab bar 上额外的元素	
     *
     * @var array
     */
    public $tabBarExtraContent;

    /**
     * PageHeader 的所有属性
     *
     * @var array
     */
    public $header;

    /**
     * 配置头部区域的背景颜色为透明
     *
     * @var bool
     */
    public $ghost = false;

    /**
     * 固定 pageHeader 的内容到顶部，如果页面内容较少，最好不要使用，会有严重的遮挡问题
     *
     * @var bool
     */
    public $fixedHeader;

    /**
     * 固钉的配置，与 antd 完全相同
     *
     * @var array
     */
    public $affixProps;

    /**
     * 悬浮在底部的操作栏，传入一个数组，会自动加空格
     *
     * @var array
     */
    public $footer;

    /**
     * 内容区
     *
     * @var array
     */
    public $body;

    /**
     * 配置水印，Layout 会透传给 PageContainer，但是以 PageContainer 的配置优先
     *
     * @var array
     */
    public $waterMarkProps;

    /**
     * Tabs 的相关属性，只有卡片样式的页签支持新增和关闭选项。使用 closable={false} 禁止关闭
     *
     * @var array
     */
    public $tabProps;

    /**
     * 动态类
     *
     * @var array
     */
    public static $classes = [
        'pageHeader' => PageContainer\PageHeader::class,
    ];

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $body
     * @return void
     */
    public function __construct($header = '', $body = [])
    {
        $this->component = 'pageContainer';
        $this->header = $header;
        $this->body = $body;

        return $this;
    }

    /**
     * 内容区
     *
     * @param  string|array  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * 额外内容区，位于 content 的右侧
     *
     * @param  array  $extraContent
     * @return $this
     */
    public function extraContent($extraContent)
    {
        $this->extraContent = $extraContent;

        return $this;
    }

    /**
     * tab 标题列表
     *
     * @param  array  $tabList
     * @return $this
     */
    public function tabList($tabList)
    {
        $this->tabList = $tabList;

        return $this;
    }

    /**
     * 当前高亮的 tab 项
     *
     * @param  string  $tabActiveKey
     * @return $this
     */
    public function tabActiveKey($tabActiveKey)
    {
        $this->tabActiveKey = $tabActiveKey;

        return $this;
    }

    /**
     * tab bar 上额外的元素
     *
     * @param  string|array  $tabBarExtraContent
     * @return $this
     */
    public function tabBarExtraContent($tabBarExtraContent)
    {
        $this->tabBarExtraContent = $tabBarExtraContent;

        return $this;
    }

    /**
     * PageHeader 的所有属性
     *
     * @param  array  $header
     * @return $this
     */
    public function header($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * 配置头部区域的背景颜色为透明
     *
     * @param  bool  $ghost
     * @return $this
     */
    public function ghost($ghost = true)
    {
        $this->ghost = $ghost;

        return $this;
    }

    /**
     * 固定 pageHeader 的内容到顶部，如果页面内容较少，最好不要使用，会有严重的遮挡问题
     *
     * @param  bool  $fixedHeader
     * @return $this
     */
    public function fixedHeader($fixedHeader = true)
    {
        $this->fixedHeader = $fixedHeader;

        return $this;
    }

    /**
     * 固钉的配置，与 antd 完全相同
     *
     * @param  array  $affixProps
     * @return $this
     */
    public function affixProps($affixProps)
    {
        $this->affixProps = $affixProps;

        return $this;
    }

    /**
     * 悬浮在底部的操作栏，传入一个数组，会自动加空格
     *
     * @param  array  $footer
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;

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
     * 配置水印，Layout 会透传给 PageContainer，但是以 PageContainer 的配置优先
     *
     * @param  array  $waterMarkProps
     * @return $this
     */
    public function waterMarkProps($waterMarkProps)
    {
        $this->waterMarkProps = $waterMarkProps;

        return $this;
    }

    /**
     * Tabs 的相关属性，只有卡片样式的页签支持新增和关闭选项。使用 closable={false} 禁止关闭
     *
     * @param  array  $tabProps
     * @return $this
     */
    public function tabProps($tabProps)
    {
        $this->tabProps = $tabProps;

        return $this;
    }

    /**
     * 获取行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        return $class = static::$classes[$method];

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public function __call($method, $parameters)
    {
        if ($className = static::getCalledClass($method)) {
            $title = $parameters[0]; // 标题
            $subTitle = $parameters[1] ?? null; // 子标题
            $element = new $className($title, $subTitle);

            return $element;
        }
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(json_encode($this->tabActiveKey).json_encode($this->tabList), true);
        }

        return array_merge([
            'content' => $this->content,
            'extraContent' => $this->extraContent,
            'tabList' => $this->tabList,
            'tabActiveKey' => $this->tabActiveKey,
            'tabBarExtraContent' => $this->tabBarExtraContent,
            'header' => $this->header,
            'ghost' => $this->ghost,
            'fixedHeader' => $this->fixedHeader,
            'affixProps' => $this->affixProps,
            'footer' => $this->footer,
            'body' => $this->body,
            'waterMarkProps' => $this->waterMarkProps,
            'tabProps' => $this->tabProps
        ], parent::jsonSerialize());
    }
}
