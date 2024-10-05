<?php

namespace QuarkCloudIO\Quark\Component\Layout;

use QuarkCloudIO\Quark\Component\Element;

class Layout extends Element
{
    /**
     * 是否缓存layout
     *
     * @var bool
     */
    public $cache = true;

    /**
     * layout 的左上角 的 title
     *
     * @var string
     */
    public $title;

    /**
     * layout 的左上角 logo 的 url
     *
     * @var string
     */
    public $logo;

    /**
     * layout 的加载态
     *
     * @var bool
     */
    public $loading = false;

    /**
     * layout 的内容区 style
     *
     * @var array
     */
    public $contentStyle = null;

    /**
     * layout 的头部行为
     *
     * @var string
     */
    public $headerActions = null;

    /**
     * layout 的布局模式, side：左侧导航，top：顶部导航，mix：混合布局
     *
     * @var string
     */
    public $layout = 'side';

    /**
     * layout 为mix布局时，顶部主题 dark | light
     *
     * @var string
     */
    public $headerTheme = 'dark';

    /**
     * layout 为mix布局时，是否自动分割菜单
     * false：菜单显示在左侧
     * true：自动分割，顶部为一级菜单，左侧为子菜单
     *
     * @var bool
     */
    public $splitMenus = false;

    /**
     * layout 的内容模式,Fluid：定宽 1200px，Fixed：自适应
     *
     * @var string
     */
    public $contentWidth = 'Fluid';

    /**
     * 导航菜单的主题，'light' | 'dark'
     *
     * @var string
     */
    public $navTheme = 'dark';

    /**
     * 主题色
     *
     * @var string
     */
    public $primaryColor = '#1890ff';

    /**
     * 是否固定 header 到顶部
     *
     * @var bool
     */
    public $fixedHeader = true;

    /**
     * 是否固定导航
     *
     * @var bool
     */
    public $fixSiderbar = false;

    /**
     * 使用 IconFont 的图标配置
     *
     * @var string
     */
    public $iconfontUrl = '';

    /**
     * 当前 layout 的语言设置，'zh-CN' | 'zh-TW' | 'en-US'
     *
     * @var string
     */
    public $locale = 'zh-CN';

    /**
     * 侧边菜单宽度
     *
     * @var number
     */
    public $siderWidth = 208;

    /**
     * 控制菜单的收起和展开
     *
     * @var bool
     */
    public $collapsed = false;

    /**
     * 菜单
     *
     * @var array
     */
    public $menu = null;

    /**
     * 页脚
     *
     * @var array
     */
    public $footer = null;

    /**
     * 内容
     *
     * @var number|string|object
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct($title = '', $body = [])
    {
        $this->component = 'layout';
        $this->title = $title;
        $this->body = $body;

        return $this;
    }

    /**
     * layout 的左上角 的 title
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * layout 的左上角的 logo
     *
     * @param  string  $logo
     * @return $this
     */
    public function logo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * layout 的加载态
     *
     * @param  bool  $loading
     * @return $this
     */
    public function loading($loading = true)
    {
        $loading ? $this->loading = true : $this->loading = false;

        return $this;
    }

    /**
     * layout 的头部行为
     *
     * @param  string  $headerActions
     * @return $this
     */
    public function headerActions($headerActions)
    {
        $this->headerActions = $headerActions;

        return $this;
    }

    /**
     * layout 的布局模式，side：右侧导航，top：顶部导航，mix：混合模式
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['side', 'top', 'mix'])) {
            throw new \Exception("Argument must be in 'side', 'top', 'mix'!");
        }

        if($layout == 'side') {
            if($this->contentWidth === 'Fixed') {
                throw new \Exception("If layout is side model,contentWidth can't set Fixed!");
            }
        }

        $this->layout = $layout;

        return $this;
    }

    /**
     * layout为mix时，顶部主题 dark | light
     *
     * @param boolean $headerTheme
     * @return $this
     */
    public function headerTheme($headerTheme)
    {
        if(!in_array($headerTheme, ['light', 'dark'])) {
            throw new \Exception("Argument must be in 'light', 'dark'!");
        }

        $this->headerTheme = $headerTheme;

        return $this;
    }

    /**
     * layout 的内容模式,Fluid：定宽 1200px，Fixed：自适应
     *
     * @param  string  $contentWidth
     * @return $this
     */
    public function contentWidth($contentWidth)
    {
        if(!in_array($contentWidth,['Fluid', 'Fixed'])) {
            throw new \Exception("Argument must be in 'Fluid', 'Fixed'!");
        }

        if($this->layout === 'side') {
            if($contentWidth === 'Fixed') {
                throw new \Exception("If layout is side model,contentWidth can't set Fixed!");
            }
        }

        $this->contentWidth = $contentWidth;

        return $this;
    }

    /**
     * 导航的主题，'light' | 'dark'
     *
     * @param  string  $navTheme
     * @return $this
     */
    public function navTheme($navTheme)
    {
        if(!in_array($navTheme,['light', 'dark'])) {
            throw new \Exception("Argument must be in 'light', 'dark'!");
        }

        $this->navTheme = $navTheme;

        return $this;
    }

    /**
     * 后台主题色
     *
     * @param  string  $primaryColor
     * @return $this
     */
    public function primaryColor($primaryColor)
    {
        if(strpos($primaryColor,'#') === false) {
            throw new \Exception("Primary color format error!");
        }

        $this->primaryColor = $primaryColor;

        return $this;
    }

    /**
     * 是否固定 header 到顶部
     *
     * @param  bool  $fixedHeader
     * @return $this
     */
    public function fixedHeader($fixedHeader = true)
    {
        $fixedHeader ? $this->fixedHeader = true : $this->fixedHeader = false;

        return $this;
    }

    /**
     * 是否固定导航
     *
     * @param  bool  $fixedHeader
     * @return $this
     */
    public function fixSiderbar($fixSiderbar = true)
    {
        $fixSiderbar ? $this->fixSiderbar = true : $this->fixSiderbar = false;

        return $this;
    }

    /**
     * 使用 IconFont 的图标配置
     *
     * @param  string  $iconfontUrl
     * @return $this
     */
    public function iconfontUrl($iconfontUrl)
    {
        $this->iconfontUrl = $iconfontUrl;

        return $this;
    }

    /**
     * 当前 layout 的语言设置，'zh-CN' | 'zh-TW' | 'en-US'
     *
     * @param  string  $locale
     * @return $this
     */
    public function locale($locale)
    {
        if($locale != false) {
            if(!in_array($locale,['zh-CN', 'zh-TW', 'en-US'])) {
                throw new \Exception("Argument must be in 'zh-CN', 'zh-TW', 'en-US'!");
            }
        }

        $this->locale = $locale;

        return $this;
    }

    /**
     * 侧边菜单宽度
     *
     * @param  string|number  $siderWidth
     * @return $this
     */
    public function siderWidth($siderWidth)
    {
        $this->siderWidth = $siderWidth;

        return $this;
    }

    /**
     * 控制菜单的收起和展开
     *
     * @param  bool  $collapsed
     * @return $this
     */
    public function collapsed($collapsed)
    {
        $collapsed ? $this->collapsed = true : $this->collapsed = false;

        return $this;
    }

    /**
     * 自动分割菜单
     *
     * @param  bool  $splitMenus
     * @return $this
     */
    public function splitMenus($splitMenus)
    {
        $splitMenus ? $this->splitMenus = true : $this->splitMenus = false;

        if($this->layout !== 'mix' && $splitMenus === true) {
            throw new \Exception("If layout is side mix,can't set splitMenus!");
        }

        $this->splitMenus = $splitMenus;

        return $this;
    }

    /**
     * 菜单
     *
     * @param  array  $menu
     * @return $this
     */
    public function menu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * 页脚
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
     * 内容
     *
     * @param  bool  $body
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
            $this->key(__CLASS__.$this->title, true);
        }

        return array_merge([
            'cache' => $this->cache,
            'title' => $this->title,
            'logo' => $this->logo,
            'loading' => $this->loading,
            'headerActions' => $this->headerActions,
            'layout' => $this->layout,
            'contentWidth' => $this->contentWidth,
            'navTheme' => $this->navTheme,
            'fixedHeader' => $this->fixedHeader,
            'fixSiderbar' => $this->fixSiderbar,
            'iconfontUrl' => $this->iconfontUrl,
            'locale' => $this->locale,
            'siderWidth' => $this->siderWidth,
            'splitMenus' => $this->splitMenus,
            'menu' => $this->menu,
            'footer' => $this->footer,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
