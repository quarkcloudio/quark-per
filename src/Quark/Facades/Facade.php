<?php

namespace QuarkCloudIO\Quark\Facades;

use RuntimeException;

class Facade
{
    /**
     * 注册组件类
     *
     * @var array
     */
    protected static $registerComponents = [
        'page' => \QuarkCloudIO\Quark\Component\Layout\Page::class,
        'pageContainer' => \QuarkCloudIO\Quark\Component\Layout\PageContainer::class,
        'layout' => \QuarkCloudIO\Quark\Component\Layout\Layout::class,
        'footer' => \QuarkCloudIO\Quark\Component\Layout\Footer::class,
        'row' => \QuarkCloudIO\Quark\Component\Grid\Row::class,
        'col' => \QuarkCloudIO\Quark\Component\Grid\Col::class,
        'card' => \QuarkCloudIO\Quark\Component\Card\Card::class,
        'space' => \QuarkCloudIO\Quark\Component\Space\Space::class,
        'statistic' => \QuarkCloudIO\Quark\Component\Statistic\Statistic::class,
        'statisticCard' => \QuarkCloudIO\Quark\Component\Statistic\StatisticCard::class,
        'descriptions' => \QuarkCloudIO\Quark\Component\Descriptions\Descriptions::class,
        'descriptionField' => \QuarkCloudIO\Quark\Component\Descriptions\Field::class,
        'table' => \QuarkCloudIO\Quark\Component\Table\Table::class,
        'column' => \QuarkCloudIO\Quark\Component\Table\Column::class,
        'toolBar' => \QuarkCloudIO\Quark\Component\Table\ToolBar::class,
        'toolBarMenu' => \QuarkCloudIO\Quark\Component\Table\ToolBar\Menu::class,
        'search' => \QuarkCloudIO\Quark\Component\Table\Search::class,
        'action' => \QuarkCloudIO\Quark\Component\Action\Action::class,
        'tpl' => \QuarkCloudIO\Quark\Component\Tpl\Tpl::class,
        'form' => \QuarkCloudIO\Quark\Component\Form\Form::class,
        'field' => \QuarkCloudIO\Quark\Component\Form\Field::class,
        'login' => \QuarkCloudIO\Quark\Component\Login\Login::class,
        'tabs' => \QuarkCloudIO\Quark\Component\Tabs\Tabs::class,
        'tabPane' => \QuarkCloudIO\Quark\Component\Tabs\TabPane::class,
        'lists' => \QuarkCloudIO\Quark\Component\Lists\Lists::class,
        'list' => \QuarkCloudIO\Quark\Component\Lists\Lists::class,
        'meta' => \QuarkCloudIO\Quark\Component\Lists\Meta::class,
        'divider' => \QuarkCloudIO\Quark\Component\Divider\Divider::class,
        'chart' => \QuarkCloudIO\Quark\Component\Chart\Chart::class,
        'line' => \QuarkCloudIO\Quark\Component\Chart\Line::class,
        'dropdown' => \QuarkCloudIO\Quark\Component\Dropdown\Dropdown::class,
        'menu' => \QuarkCloudIO\Quark\Component\Menu\Menu::class
    ];

    /**
     * 获取组件实例
     *
     * @return mixed
     */
    public static function getComponentInstance()
    {
        $className = static::getFacadeClass();

        return new static::$registerComponents[$className]();
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getComponentInstance();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
