<?php

namespace App\Admin\Actions;

use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\QuarkAdmin\Actions\Modal;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\Quark\Facades\Tpl;
use QuarkCMS\Quark\Facades\Space;
use Illuminate\Support\Facades\DB;

class Import extends Modal
{
    /**
     * 行为名称，当行为在表格行展示时，支持js表达式
     *
     * @var string
     */
    public $name = '导入数据';

    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'primary';

    /**
     * 内容
     * 
     * @return $string
     */
    public function body()
    {
        return Form::key('importModalForm')
        ->api('admin/'.request()->route('resource').'/import')
        ->items($this->fields())
        ->labelCol([
            'span' => 6
        ])
        ->wrapperCol([
            'span' => 18
        ]);
    }

    /**
     * 充值字段
     *
     * @return array
     */
    public function fields()
    {
        return [
            Space::body(
                [
                    Tpl::body('模板文件: <a href="/api/admin/'.request()->route('resource').'/import/template?token='.get_admin_token().'" target="_blank">下载模板</a>')->style(['marginLeft'=>'50px'])
                ]
            )
            ->direction('vertical')
            ->size('middle')
            ->style(['marginBottom'=>'20px']),

            Field::file('fileId','导入文件')
            ->limitNum(1)
            ->limitType([
                'application/vnd.ms-excel',
            ])
            ->help('请上传xls格式的文件')
        ];
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
            ->withLoading()
            ->reload('table')
            ->type('primary')
            ->actionType('submit')
            ->submitForm('importModalForm')
        ];
    }
}