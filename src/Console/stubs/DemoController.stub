<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Container;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Form;
use QuarkCMS\QuarkAdmin\Card;
use QuarkCMS\QuarkAdmin\Show;
use QuarkCMS\QuarkAdmin\Components\Statistic;
use QuarkCMS\QuarkAdmin\Components\Upgrade;
use Quark;
use DB;

class DemoController extends Controller
{
    /**
     * Index demo
     *
     * @return object
     */
    public function index()
    {
        $content[] = Card::make()->title('Table 组件')->gutter(8)->content($this->table());
        $content[] = Card::make()->title('Form 组件')->style(['marginTop'=>'20px'])->gutter(8)->content($this->form());

        $container = Container::make('演示',$content);

        return success('获取成功！','',$container);
    }

    /**
     * Form demo
     *
     * @return object
     */
    public function form()
    {
        $form = Form::make()->api(backend_url('api/admin/form/store'))->style(['marginTop'=>'40px'])->labelCol(['span' => 4]);

        $form->display('纯展示');
        $form->text('input','输入框');
        $form->number('number','数字输入');
        $form->textArea('textArea','文本域');
        $form->select('select','下拉选择')->options([1 => 'a', 2 => 'b', 3 => 'c'])->load('select1','admin/demo/suggest');
        $form->select('select1','下拉选择联动');
        $form->cascader('editor', '级联菜单')->options([
            [
                'value' => 'zhejiang',
                'label' => 'Zhejiang',
                'children' => [
                    [
                        'value' => 'hangzhou',
                        'label' => 'Hangzhou'
                    ]
                ]
            ]
        ]);
        $form->search('search', '搜索选择')->api('admin/demo/suggest');
        $form->icon('icon', '图标选择');
        $form->checkbox('checkbox','多选')->options([
            1 => '首页推荐',
            2 => '频道推荐',
            3 => '列表推荐',
            4 => '详情推荐'
        ]);
        $form->radio('radio','单选')->options([
            1 => '无图',
            2 => '单图（小）',
            3 => '多图',
            4 => '单图（大）'
        ])->default(1);
        $form->date('date', '日期');
        $form->dateRange('dateRange', '日期范围');
        $form->datetime('datetime', '时间日期');
        $form->datetimeRange('datetimeRange', '时间日期范围');
        $form->time('time', '时间');
        $form->timeRange('timeRange', '时间范围');
        $form->image('image','图片上传')->mode('m')->limitNum(1);
        $form->file('file','文件上传');

        $form->list('list', '嵌套表单')->button('添加数据')->item(function ($form) {
            $form->text('input','输入框');
            $form->number('number','数字输入');
        });

        $treeData = [
            [
                'key' => 'Node1',
                'title' => 'Node1',
                'value' => '0-0',
                'children' => [
                    [
                        'key' => 'ChildNode1',
                        'title' => 'Child Node1',
                        'value' => '0-0-1',
                    ],
                    [
                        'key' => 'ChildNode2',
                        'title' => 'Child Node2',
                        'value' => '0-0-2',
                    ],
                ],
            ],
            [
                'key' => 'Node2',
                'title' => 'Node2',
                'value' => '0-1',
            ]
        ];

        $form->tree('tree', '树形选择')->data($treeData);
        $form->map('map', '地图')->style(['width'=>'100%','height'=>400]);
        $form->editor('editor','编辑器');
        $form->switch('switch','开关')->options([
            'on'  => '是',
            'off' => '否'
        ])->default(true);

        return $form;
    }

    /**
     * Table demo
     *
     * @return object
     */
    public function table()
    {
        $table = Table::make()->headerTitle('这里是标题')->tableLayout('fixed');

        $table->column('id','序号');
        $table->column('avatar','头像')->image()->width(60);
        $table->column('username','用户名')->editLink()->width(120);
        $table->column('nickname','昵称')->editable()->width(120);
        $table->column('email','邮箱')->ellipsis()->copyable()->width(160);
        $table->column('phone','手机号')->sorter()->width(100);
        $table->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);
        $table->column('actions','操作')->width(120)->actions(function($action,$row) {
            $action->a('编辑');
            $action->a('删除')->withPopconfirm('确认要删除吗？');
        });

        $table->toolBar()->actions(function($action) {
            $action->button('创建管理员')->type('primary')->icon('plus-circle');
        });

        // 批量操作
        $table->batchActions(function($action) {
            $action->a('批量删除')->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
            $action->dropdown('更多')->overlay(function($action) {
                $action->item('禁用用户')->withConfirm('确认要禁用吗？','禁用后管理员将无法登陆后台，请谨慎操作！');
            });
        });

        // 搜索
        $table->search(function($search) {
            $search->where('username', '搜索内容',function ($model) {
                $model->where('username', 'like', "%{input}%");
            })->placeholder('名称');
            $search->equal('status', '所选状态')->select([''=>'全部', 1=>'正常', 0=>'已禁用'])->load('status1', 'admin/demo/suggest')->width(110);
            $search->equal('status1', '联动选择')->select()->width(110);
            $search->between('last_login_time', '登录时间')->datetime();
        });

        return $table;
    }
    
    /**
     * Form suggest demo
     *
     * @return object
     */
    public function suggest(Request $request)
    {
        // 获取参数
        $search = $request->input('search');

        switch ($search) {
            case 1:
                $options = [
                    [
                        'value' => 4,
                        'label' => 'd'
                    ],
                    [
                        'value' => 5,
                        'label' => 'e'
                    ]
                ];
                break;

            case 2:
                $options = [
                    [
                        'value' => 6,
                        'label' => 'f'
                    ],
                    [
                        'value' => 7,
                        'label' => 'g'
                    ]
                ];
                break;

            default:
                $options = [];
                break;
        }

        return success('获取成功','',$options);
    }
}