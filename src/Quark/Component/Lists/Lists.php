<?php

namespace QuarkCloudIO\Quark\Component\Lists;

use QuarkCloudIO\Quark\Component\Lists\Meta;
use QuarkCloudIO\Quark\Component\Element;
use Closure;
use Exception;

class Lists extends Element
{
    /**
     * 行主键
     *
     * @var string
     */
    public $rowKey = 'id';

    /**
     * 获取表格数据接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 获取表格数据接口类型
     *
     * @var string
     */
    public $apiType = 'GET';

    /**
     * 表头标题
     *
     * @var string
     */
    public $headerTitle;

    /**
     * 表格列描述
     *
     * @var object
     */
    public $metas;
    
    /**
     * 批量操作选择项
     *
     * @var array
     */
    public $rowSelection = false;

    /**
     * 设置显示斑马线样式
     *
     * @var bool
     */
    public $striped = false;

    /**
     * 表格数据
     *
     * @var array|string
     */
    public $datasource = null;

    /**
     * 表格分页
     *
     * @var array|bool
     */
    public $pagination = false;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct()
    {
        $this->component = 'list';

        return $this;
    }

    /**
     * 获取表格数据接口
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * 获取表格数据接口类型
     *
     * @param  string  $apiType
     * @return $this
     */
    public function apiType($apiType)
    {
        $this->apiType = $apiType;

        return $this;
    }

    /**
     * 表头标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->headerTitle($title);

        return $this;
    }

    /**
     * 表头标题
     *
     * @param  string  $headerTitle
     * @return $this
     */
    public function headerTitle($headerTitle)
    {
        $this->headerTitle = $headerTitle;

        return $this;
    }

    /**
     * 批量设置表格列
     *
     * @param array $metas
     * @return $this
     */
    public function metas($metas)
    {
        $keys = array_keys($metas);

        foreach ($keys as $value) {
            if(!in_array($value,[
                'type',
                'title',
                'subTitle',
                'description',
                'avatar',
                'actions',
                'content',
                'extra'
            ])) {
                throw new Exception("meta index key must be in 'type','title','subTitle','description','avatar','actions','content','extra'!");
            }
        }

        $this->metas = $metas;

        return $this;
    }

    /**
     * 批量操作选择项
     *
     * @param array $rowSelection
     * @return $this
     */
    public function rowSelection($rowSelection)
    {
        $this->rowSelection = $rowSelection;

        return $this;
    }

    /**
     * 设置表格滚动
     *
     * @param  bool  $striped
     * @return $this
     */
    public function striped($striped = true)
    {
        $this->striped = $striped;

        return $this;
    }

    /**
     * 透传 ProUtils 中的 ListToolBar 配置项
     *
     * @param  void
     * @return $this
     */
    public function toolBar($toolBar)
    {
        $this->toolBar = $toolBar;

        return $this;
    }

    /**
     * 表格数据
     *
     * @param  array|string  $datasource
     * @return $this
     */
    public function datasource($datasource)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * 表格分页
     *
     * @param  number  $current
     * @param  number  $pageSize
     * @param  number  $total
     * @param  number  $defaultCurrent
     * @return $this
     */
    public function pagination($current, $pageSize, $total, $defaultCurrent = 1)
    {
        $pagination['defaultCurrent'] = $defaultCurrent;
        $pagination['current'] = $current;
        $pagination['pageSize'] = $pageSize;
        $pagination['total'] = $total;

        $this->pagination = $pagination;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->headerTitle.json_encode($this->metas), true);
        }

        return array_merge([
            'api' => $this->api,
            'apiType' => strtoupper($this->apiType),
            'rowKey' => $this->rowKey,
            'headerTitle' => $this->headerTitle,
            'metas' => $this->metas,
            'rowSelection' => $this->rowSelection,
            'striped' => $this->striped,
            'toolBar' => $this->toolBar,
            'datasource' => $this->datasource,
            'pagination' => $this->pagination
        ], parent::jsonSerialize());
    }
}