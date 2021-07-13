<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;
use QuarkCMS\Quark\Facades\TabPane;

class Article extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '文章';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'App\Models\Post';

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
        return $query->orderBy('id','desc')->where('type','ARTICLE');
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
            (TabPane::make('基本', $this->baseFields())),
            (TabPane::make('扩展', $this->extendFields()))
        ];
    }

    /**
     * 基础标签页字段
     *
     * @return array
     */
    public function baseFields()
    {
        return [
            Field::hidden('id','ID')
            ->onlyOnForms(),

            Field::text('title','标题', function() {
                return "<a href='#/index?api=admin/post/edit&id=" . $this->id . "'>" . $this->title . "</a>";
            })
            ->rules(
                ['required','min:6','max:20'],
                ['required' => '标题必须填写','min' => '标题不能少于6个字符','max' => '标题不能超过20个字符']
            ),

            Field::text('author','作者'),

            Field::text('source','来源'),

            Field::text('description','描述'),
            
            Field::editor('content','内容')
            ->onlyOnForms(),

            Field::image('cover_ids','封面图')
            ->onlyOnForms(),

            Field::switch('status','状态')
            ->editable()
            ->trueValue('正常')
            ->falseValue('禁用')
            ->default(true),
        ];
    }

    /**
     * 扩展标签页字段
     *
     * @return array
     */
    public function extendFields()
    {
        return [
            Field::text('name','缩略名'),

            Field::number('level','排序')
            ->value(0),

            Field::number('view','浏览量')
            ->value(0),

            Field::number('comment','评论量')
            ->value(0),

            Field::text('password','访问密码')
            ->onlyOnForms(),

            Field::file('file_ids','附件')
            ->onlyOnForms(),

            Field::switch('comment_status','允许评论')
            ->editable()
            ->trueValue('是')
            ->falseValue('否')
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
            new \App\Admin\Searches\Input('username', '用户名'),
            new \App\Admin\Searches\Input('nickname', '昵称'),
            new \App\Admin\Searches\Status,
            new \App\Admin\Searches\DateTimeRange('last_login_time', '登录时间')
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
            (new \App\Admin\Actions\CreateLink($this->title()))->onlyOnIndex(),
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\Disable('批量禁用'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\Enable('批量启用'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\ChangeStatus)->onlyOnTableRow(),
            (new \App\Admin\Actions\EditLink('编辑'))->onlyOnTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnTableRow(),
        ];
    }
}