<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;

class Config extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '配置';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Config';

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = 10;

    /**
     * 列表查询
     *
     * @param  Request  $request
     * @return object
     */
    public static function indexQuery(Request $request, $query)
    {
        return $query->orderBy('id', 'desc');
    }

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
            ->rules(
                ['required'],
                ['required' => '分组名称必须填写']
            )
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

    /**
     * 搜索表单
     *
     * @param  Request  $request
     * @return object
     */
    public function searches(Request $request)
    {
        return [
            new \App\Admin\Searches\Input('title', '标题'),
            new \App\Admin\Searches\Input('name', '名称'),
            new \App\Admin\Searches\Status
        ];
    }

    /**
     * 行为
     *
     * @param  Request  $request
     * @return object
     */
    public function actions(Request $request)
    {
        return [
            (new \App\Admin\Actions\CreateModal($this->title()))->onlyOnIndex(),
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\Disable('批量禁用'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\Enable('批量启用'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\ChangeStatus)->onlyOnIndexTableRow(),
            (new \App\Admin\Actions\EditModal('编辑'))->onlyOnIndexTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnIndexTableRow(),
            new \App\Admin\Actions\FormSubmit,
            new \App\Admin\Actions\FormReset,
            new \App\Admin\Actions\FormBack,
            new \App\Admin\Actions\FormExtraBack
        ];
    }
}