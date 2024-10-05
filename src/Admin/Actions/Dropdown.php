<?php

namespace QuarkCloudIO\QuarkAdmin\Actions;

use QuarkCloudIO\Quark\Facades\Menu;

/**
 * Class Dropdown.
 */
abstract class Dropdown extends Action
{
    /**
     * 下拉框箭头是否显示
     *
     * @var bool
     */
    public $arrow = true;

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、modal、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'dropdown';

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @var string
     */
    public $placement = 'bottomLeft';

    /**
     * 触发下拉的行为, 移动端不支持 hover,Array<click|hover|contextMenu>
     *
     * @var bool
     */
    public $trigger = ['hover'];

    /**
     * 下拉根元素的样式
     *
     * @var array
     */
    public $overlayStyle = null;

    /**
     * 菜单行为
     *
     * @var array
     */
    public $actions = [];

    /**
     * 是否显示箭头图标
     *
     * @param  void
     * @return $this
     */
    public function arrow()
    {
        return $this->arrow;
    }

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @param  void
     * @return $this
     */
    public function placement()
    {
        return $this->placement;
    }

    /**
     * 触发下拉的行为, 移动端不支持 hover,Array<click|hover|contextMenu>
     *
     * @param void
     * @return $this
     */
    public function trigger()
    {
        return $this->trigger;
    }

    /**
     * 下拉根元素的样式
     *
     * @param  void
     * @return $this
     */
    public function overlayStyle()
    {
        return $this->overlayStyle;
    }

    /**
     * 菜单
     *
     * @return array
     */
    public function overlay()
    {
        foreach ($this->getActions() as $key => $value) {
            $items[] = $this->buildAction($value);
        }

        return Menu::make($items);
    }

    /**
     * 创建行为组件
     *
     * @param  object  $item
     * @return array
     */
    protected function buildAction($item)
    {
        $builder = Menu::item($item->name())
        ->withLoading($item->withLoading())
        ->reload($item->reload())
        ->api($item->api())
        ->actionType($item->actionType())
        ->type($item->type())
        ->size($item->size());

        if($item->icon()) {
            $builder->icon($item->icon());
        }

        if($item->actionType() === 'link') {
            $builder->style(['color'=>'#1890ff'])
            ->link($item->href(), $item->target());
        }

        if($item->actionType() === 'modal') {
            $builder->modal(function($modal) use ($item) {
                return $modal->title($item->name())
                ->width($item->width())
                ->body($item->body())
                ->destroyOnClose($item->destroyOnClose())
                ->actions($item->actions());
            });
        }

        if($item->actionType() === 'drawer') {
            $builder->drawer(function($drawer) use ($item) {
                return $drawer->title($item->name())
                ->width($item->width())
                ->body($item->body())
                ->destroyOnClose($item->destroyOnClose())
                ->actions($item->actions());
            });
        }

        if($item->confirmTitle) {
            $builder->withConfirm($item->confirmTitle, $item->confirmText, $item->confirmType);
        }

        return $builder->render();
    }

    /**
     * 下拉菜单行为
     * @param  array  $actions
     * @return $actions
     */
    public function actions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * 获取下拉菜单行为
     *
     * @return $actions
     */
    public function getActions()
    {
        return $this->actions;
    }
}
