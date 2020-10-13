<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TabForm extends Form
{
    /**
     * Tab布局的表单
     *
     * @var array
     */
    public $tab = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct(Eloquent $model)
    {
        $this->component = 'tabForm';
        $this->model = $model;

        // 初始化表单提交地址
        $this->initApi();

        // 初始化表单提交数据
        $this->initData();

        return $this;
    }

    /**
     *  表单默认值，只有初始化以及重置时生效
     *
     * @param  array  $initialValues
     * @return $this
     */
    public function initialValues($initialValues = null)
    {
        $data = [];

        if(isset($this->items)) {
            foreach ($this->items as $key => $item) {
                if(isset($item->name)) {
                    if(isset($item->name)) {
                        if(isset($item->defaultValue)) {
                            $data[$item->name] = $item->defaultValue;
                        }

                        if(isset($initialValues[$item->name])) {
                            $data[$item->name] = $initialValues[$item->name];
                        }

                        if(isset($item->value)) {
                            $data[$item->name] = $item->value;
                        }
                    }
                }
            }
        }

        $this->initialValues = $data;
        return $this;
    }

    /**
     * tab布局的表单
     *
     * @return bool
     */
    public function tab($title,Closure $callback = null)
    {
        $callback($this);

        $tab['title'] = $title;
        if(isset($this->items)) {
            $tab['items'] = $this->items;
            $this->items = [];
        }
        $this->form['tab'][] = $tab;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'tab' => $this->tab
        ], parent::jsonSerialize());
    }
}
