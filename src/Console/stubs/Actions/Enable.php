<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class Enable extends Action
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
    public $showStyle = 'link';

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'small';

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

        $this->withConfirm('确定要启用吗？', '启用后数据将正常使用！');
    }

    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $models
     * @return mixed
     */
    public function handle($fields, $models)
    {
        foreach ($models as $model) {
            $model->update([
                'status' => 1
            ]);
        }

        return success('操作成功！');
    }
}