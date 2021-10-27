<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class Disable extends Action
{
    /**
     * 行为名称
     *
     * @var string
     */
    public $name = null;

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

        $this->withConfirm('确定要禁用吗？', '禁用后数据将无法使用，请谨慎操作！');
    }

    /**
     * 行为接口接收的参数，当行为在表格行展示的时候，可以配置当前行的任意字段
     *
     * @return array
     */
    public function apiParams()
    {
        return ['id'];
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
            'status' => 0
        ]);

        return $result ? success('操作成功！') : error('操作失败，请重试！');
    }
}