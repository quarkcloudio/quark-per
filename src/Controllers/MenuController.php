<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Helper;
use QuarkCMS\QuarkAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use Quark;

class MenuController extends QuarkController
{
    public $title = '菜单';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $grid = Quark::grid(new Menu)
        ->title($this->title)
        ->tree();

        $grid->column('name','名称')->link()->width(200);
        $grid->column('sort','排序')->editable()->width('12%');
        $grid->column('icon','图标');
        $grid->column('path','路由');
        $grid->column('show','显示')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '显示'],
            'off' => ['value' => 0, 'text' => '隐藏']
        ])->width(100);
        $grid->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 2, 'text' => '禁用']
        ])->width(100);
        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('edit', '编辑');
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('createWithModal', '创建')->withModal('创建菜单',function($modal) {
                $modal->disableFooter();
                $modal->form->ajax('admin/menu/create');
            });
            $action->button('refresh', '刷新');
        });

        // select样式的批量操作
        $grid->batchActions(function($batch) {
            $batch->option('', '批量操作');
            $batch->option('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        })->style('select',['width'=>120]);

        $grid->search(function($search) {
            $search->where('name', '搜索内容',function ($query) {
                $query->where('name', 'like', "%{input}%");
            })->placeholder('名称');
        })->expand(false);

        $grid->disableAdvancedSearch();

        $grid->model()
        ->orderBy('sort', 'asc')
        ->paginate(100);

        return $grid;
    }

    /**
     * 表单页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function form()
    {
        $form = Quark::form(new Menu);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->title($title);
        
        $form->id('id','ID');

        $form->text('name','名称')
        ->rules(['required','max:20'],['required'=>'名称必须填写','max'=>'名称不能超过20个字符']);

        $form->select('pid','父节点')
        ->options(['1' => '男', '2'=> '女'])
        ->default(1);

        $form->radio('sex','性别')
        ->options(['1' => '男', '2'=> '女'])
        ->default(1);

        $form->text('email','邮箱')
        ->rules(['required','email','max:255'],['required'=>'邮箱必须填写','email'=>'邮箱格式错误','max'=>'邮箱不能超过255个字符'])
        ->creationRules(["unique:admins"],['unique'=>'邮箱已经存在',])
        ->updateRules(["unique:admins,email,{{id}}"],['unique'=>'邮箱已经存在']);

        $form->text('phone','手机号')
        ->rules(['required','max:11'],['required'=>'手机号必须填写','max'=>'手机号不能超过11个字符'])
        ->creationRules(["unique:admins"],['unique'=>'手机号已经存在'])
        ->updateRules(["unique:admins,phone,{{id}}"],['unique'=>'手机号已经存在']);

        $form->text('password','密码')
        ->creationRules(["required"],['required'=>'密码不能为空']);

        //保存前回调
        $form->saving(function ($form) {

            if(isset($form->request['avatar']['id'])) {
                $form->request['avatar'] = $form->request['avatar']['id'];
            }

            if(isset($form->request['password'])) {
                $form->request['password'] = bcrypt($form->request['password']);
            }
        });

        return $form;
    }

    // /**
    //  * Form页面模板
    //  * 
    //  * @param  Request  $request
    //  * @return Response
    //  */
    // public function form($data = [])
    // {
    //     if(isset($data['id'])) {
    //         $action = 'admin/'.$this->controllerName().'/save';
    //     } else {
    //         $action = 'admin/'.$this->controllerName().'/store';
    //     }

    //     // 查询列表
    //     $menus = Menu::query()->where('guard_name','admin')
    //     ->orderBy('sort', 'asc')
    //     ->orderBy('id', 'asc')
    //     ->get()
    //     ->toArray();

    //     $menuTrees = Helper::listToTree($menus,'id','pid','children',0);

    //     $menuTreeLists = Helper::treeToOrderList($menuTrees,0,'name','children');

    //     $list = [];
    //     foreach ($menuTreeLists as $key => $menuTreeList) {
    //         $list[] = [
    //             'name'=>$menuTreeList['name'],
    //             'value'=>$menuTreeList['id'],
    //         ];
    //     }

    //     $permissions = Permission::all();

    //     $getPermissions = [];
    //     foreach ($permissions as $key => $permission) {
    //         $getPermissions[] = [
    //             'name'=>$permission['name'],
    //             'value'=>$permission['id'],
    //         ];
    //     }

    //     $controls = [
    //         ID::make('ID','id'),
    //         Input::make('名称','name')->style(['width'=>200]),
    //         Select::make('父节点','pid')->option($list)->style(['width'=>200]),
    //         InputNumber::make('排序','sort')->style(['width'=>200])->value(0),
    //         Icon::make('图标','icon')->style(['width'=>200]),
    //         Input::make('路由','path')->style(['width'=>200]),
    //         Select::make('绑定权限','permission_ids')->mode('tags')->option($getPermissions)->style(['width'=>350]),
    //         SwitchButton::make('显示','show')->checkedText('是')->unCheckedText('否')->value(true),
    //         SwitchButton::make('状态','status')->checkedText('正常')->unCheckedText('禁用')->value(true),
    //         Button::make('提交')
    //         ->type('primary')
    //         ->style(['width'=>100,'float'=>'left','marginLeft'=>350])
    //         ->onClick('submit',null,$action)
    //     ];

    //     $labelCol['sm'] = ['span'=>4];
    //     $wrapperCol['sm'] = ['span'=>20];

    //     $result = $this->formBuilder($controls,$data,$labelCol,$wrapperCol);

    //     return $result;
    // }

    /**
     * 保存方法
     * 
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $name          =   $request->json('name','');
        $pid           =   $request->json('pid',0);
        $sort          =   $request->json('sort',0);
        $icon          =   $request->json('icon','');
        $path          =   $request->json('path');
        $permissionIds =   $request->json('permission_ids','');
        $show          =   $request->json('show');
        $status        =   $request->json('status','');
        
        if (empty($name)) {
            return $this->error('名称必须填写！');
        }

        if ($show == true) {
            $show = 1;
        } else {
            $show = 0; //隐藏
        }

        if ($status == true) {
            $status = 1;
        } else {
            $status = 2; //禁用
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $path;
        $data['show'] = $show;
        $data['status'] = $status;

        $result = Menu::create($data);

        if($permissionIds) {
            Permission::whereIn('id',$permissionIds)->update(['menu_id' => $result['id']]);
        }

        if($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    /**
     * 编辑页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return $this->error('参数错误！');
        }

        $data = Menu::find($id)->toArray();

        $permissionIds= Permission::where('menu_id',$id)->pluck('id');

        foreach ($permissionIds as $key => $value) {
            $data['permission_ids'][] = strval($value);
        }

        $data = $this->form($data);
        
        return $this->success('获取成功！','',$data);
    }

    /**
     * 保存编辑数据
     *
     * @param  Request  $request
     * @return Response
     */
    public function save(Request $request)
    {
        $id            =   $request->json('id');
        $name          =   $request->json('name','');
        $pid           =   $request->json('pid',0);
        $sort          =   $request->json('sort',0);
        $icon          =   $request->json('icon','');
        $path          =   $request->json('path');
        $permissionIds =   $request->json('permission_ids','');
        $show          =   $request->json('show');
        $status        =   $request->json('status','');

        if (empty($id)) {
            return $this->error('请选择数据！');
        }
        
        if (empty($name)) {
            return $this->error('名称必须填写！');
        }

        if ($show == true) {
            $show = 1;
        } else {
            $show = 0; //隐藏
        }

        if ($status == true) {
            $status = 1;
        } else {
            $status = 2; //禁用
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $path;
        $data['show'] = $show;
        $data['status'] = $status;

        $result = Menu::where('id',$id)->update($data);

        if($permissionIds) {

            // 先清空
            Permission::where('menu_id',$id)->update(['menu_id' => 0]);

            // 赋值
            Permission::whereIn('id',$permissionIds)->update(['menu_id' => $id]);
        }

        if ($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }
}