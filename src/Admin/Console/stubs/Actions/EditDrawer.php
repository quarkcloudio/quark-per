<?php

namespace App\Admin\Actions;

use QuarkCloudIO\Quark\Facades\Form;
use QuarkCloudIO\QuarkAdmin\Actions\Drawer;
use QuarkCloudIO\QuarkAdmin\Http\Requests\ResourceEditRequest;
use QuarkCloudIO\Quark\Facades\Action;

class EditDrawer extends Drawer
{
    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'link';

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'small';

    /**
     * 关闭时销毁 Modal 里的子元素，如果包含editor组件时设置false
     *
     * @var bool
     */
    public $destroyOnClose = true;

    /**
     * 执行成功后刷新的组件
     *
     * @var string
     */
    public $reload = 'table';

    /**
     * 初始化
     *
     * @param  string  $name
     * 
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 内容
     * 
     * @return $string
     */
    public function body()
    {
        $request = new ResourceEditRequest;

        // 表单
        return Form::key('editDrawerForm')
        ->api($request->newResource()->updateApi($request))
        ->initApi($request->newResource()->editValueApi($request))
        ->items($request->newResource()->updateFieldsWithinComponents($request))
        ->labelCol([
            'span' => 6
        ])
        ->wrapperCol([
            'span' => 18
        ]);
    }

    /**
     * 弹窗行为
     *
     * @return $this
     */
    public function actions()
    {
        return [
            Action::make('取消')->actionType('cancel'),

            Action::make("提交")
            ->reload('table')
            ->type('primary')
            ->actionType('submit')
            ->submitForm('editDrawerForm')
        ];
    }
}