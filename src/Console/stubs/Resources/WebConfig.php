<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;

class WebConfig extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '网站配置';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Config';

    /**
     * 创建表单的接口
     *
     * @var string
     */
    public static $creationApi = 'www.baidu.com';

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Field::hidden('id','ID')
            ->onlyOnForms(),

            Field::text('title','标题')
            ->editable()
            ->rules(
                ['required'],
                ['required' => '标题必须填写']
            ),

            Field::select('type','表单类型')
            ->options([
                'text'=>'输入框',
                'textarea'=>'文本域',
                'picture'=>'图片',
                'file'=>'文件',
                'switch'=>'开关'
            ])
            ->default('text')
            ->onlyOnForms(),

            Field::text('name','名称')
            ->editable()
            ->rules(['required','max:255'],['required'=>'名称必须填写','max'=>'名称不能超过255个字符'])
            ->creationRules(["unique:configs"],['unique'=>'名称已经存在'])
            ->updateRules(["unique:configs,name,{id}"],['unique'=>'名称已经存在']),
            
            Field::text('group_name','分组名称')
            ->onlyOnForms(),

            Field::textArea('remark','备注')
            ->rules(['max:255'],['max'=>'备注不能超过255个字符']),

            Field::switch('status','状态')
            ->editable()
            ->trueValue('正常')
            ->falseValue('禁用')
            ->default(true)
        ];
    }
}