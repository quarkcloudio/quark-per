<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class ChangeStatus extends Action
{
    /**
     * 行为名称，当行为在表格行展示时，支持js表达式
     *
     * @var string
     */
    public $name = "<%= (status==1 ? '禁用' : '启用') %>";

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
     * 初始化
     *
     * @param  void
     * @return void
     */
    public function __construct()
    {
        // 当行为在表格行展示时，支持js表达式
        $this->withConfirm("确定要<%= (status==1 ? '禁用' : '启用') %>数据吗？", null, 'pop');
    }

    /**
     * 接口接收的参数
     *
     * @return string
     */
    public function apiParams()
    {
        return ['id', 'status'];
    }

    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $model
     * @return mixed
     */
    public function handle($fields, $model)
    {
        $result = $model->update([
            'status' => !$fields['status']
        ]);

        return $result ? success('操作成功！') : error('操作失败，请重试！');
    }
}