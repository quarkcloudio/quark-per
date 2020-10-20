<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Config;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Form;
use QuarkCMS\QuarkAdmin\TabForm;
use QuarkCMS\QuarkAdmin\Container;
use Cache;

class ConfigController extends Controller
{
    public $title = '配置';

    /**
     * Form页面模板
     * 
     * @param  Request  $request
     * @return Response
     */
    public function websiteForm()
    {
        $groupNames = Config::where('status', 1)
        ->distinct()
        ->pluck('group_name');

        $form = new TabForm(new Config);

        $form->api('admin/config/saveWebsite');

        foreach ($groupNames as $key => $groupName) {
            if($groupName) {
                $configs = Config::where('status', 1)
                ->where('group_name',$groupName)
                ->get()
                ->toArray();
    
                $form->tab($groupName, function ($form) use ($configs) {
                    foreach ($configs as $key => $config) {
                        switch ($config['type']) {
                            case 'text':
                                $form->text($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->value($config['value']);
                                break;
                            case 'file':
                                $files = null;
                                if($config['value']) {
                                    $file['id'] = $config['value'];
                                    $file['uid'] = $config['value'];
                                    $file['name'] = get_file($config['value'],'name');
                                    $file['size'] = get_file($config['value'],'size');
                                    $file['url'] = get_file($config['value'],'path');
                                    $files[] = $file;
                                }

                                $form->file($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->button('上传'.$config['title'])
                                ->value($files);

                                break;
                            case 'textarea':
                                $form->textArea($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->value($config['value']);
                                break;
                            case 'switch':
                                $form->switch($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->options([
                                    'on'  => '开启',
                                    'off' => '关闭'
                                ])->value($config['value']);

                                break;
                            case 'picture':

                                $image = null;
                                if($config['value']) {
                                    $image['id'] = $config['value'];
                                    $image['name'] = get_picture($config['value'],0,'name');
                                    $image['size'] = get_picture($config['value'],0,'size');
                                    $image['url'] = get_picture($config['value'],0,'path');
                                }

                                $form->image($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->button('上传'.$config['name'])
                                ->value($image);

                                break;
                            default:
                                $form->text($config['name'],$config['title'])
                                ->extra($config['remark'])
                                ->value($config['value']);
                                break;
                        }
                    }
                });
            }
        }

        return $form;
    }

    /**
     * 网站设置
     *
     * @param  Request  $request
     * @return Response
     */
     public function website(Request $request)
     {
        $form = $this->websiteForm()->initialValues();

        // 初始化容器
        $container = new Container();

        // 设置标题
        $container->title($this->title());

        // 设置二级标题
        $container->subTitle($this->subTitle());

        // 设置面包屑导航
        $container->breadcrumb($this->breadcrumb());

        // 设置内容
        $container->content($form);

        return success('获取成功！','',$container);
    }

    /**
    * 保存站点配置数据
    *
    * @param  Request  $request
    * @return Response
    */
    public function saveWebsite(Request $request)
    {

        $requestJson    =   $request->getContent();
        $requestData    =   json_decode($requestJson,true);


        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        if(!is_writable($envPath)) {
            return error('操作失败，请检查.env文件是否具有写入权限');
        }

        $result = true;
        // 遍历插入数据
        foreach ($requestData as $key => $value) {
            // 修改时清空缓存
            Cache::pull($key);

            $config = Config::where('name',$key)->first();

            if($config['name'] == 'APP_DEBUG') {

                if($value) {
                    $data = [
                        'APP_DEBUG' => 'true'
                    ];
                } else {
                    $data = [
                        'APP_DEBUG' => 'false'
                    ];
                }

                modify_env($data);
            }

            $getResult = Config::where('name',$key)->update(['value'=>$value]);
            if($getResult === false) {
                $result = false;
            }
        }

        if ($result) {
            return success('操作成功！','');
        } else {
            return error('操作失败！');
        }
    }

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Config);
        $table->headerTitle($this->title.'列表')->tableLayout('fixed');
        
        $table->column('id','序号')->width(80);
        $table->column('title','标题')->width(120);
        $table->column('name','名称')->copyable();
        $table->column('remark','备注')->ellipsis();
        $table->column('status','状态')->using(['1'=>'正常','0'=>'禁用'])->width(60);
        $table->column('actions','操作')->width(120)->actions(function($action,$row) {

            // 根据不同的条件定义不同的A标签形式行为
            if($row['status'] === 1) {
                $action->a('禁用')
                ->withPopconfirm('确认要禁用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>0]);
            } else {
                $action->a('启用')
                ->withPopconfirm('确认要启用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>1]);
            }

            // 跳转默认编辑页面
            $action->a('编辑')->modalForm(backend_url('api/admin/config/edit?id='.$row['id']));

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            return $action;
        });

        $table->toolBar()->actions(function($action) {

            // 跳转默认创建页面
            $action->button('创建配置')
            ->type('primary')
            ->icon('plus-circle')
            ->modalForm(backend_url('api/admin/config/create'));

            return $action;
        });

        // 批量操作
        $table->batchActions(function($action) {
            // 跳转默认编辑页面
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->model()
            ->whereIn('id','{ids}')
            ->delete();

            // 下拉菜单形式的行为
            $action->dropdown('更多')->overlay(function($action) {
                $action->item('禁用配置')
                ->withConfirm('确认要禁用吗？','禁用后配置将不再生效，请谨慎操作！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>0]);

                $action->item('启用配置')
                ->withConfirm('确认要启用吗？','启用后配置将可以生效！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>1]);

                return $action;
            });
        });

        // 搜索
        $table->search(function($search) {

            $search->where('title', '搜索内容',function ($model) {
                $model->where('title', 'like', "%{input}%");
            })->placeholder('标题');

            $search->equal('status', '所选状态')
            ->select([''=>'全部', 1=>'正常', 0=>'已禁用'])
            ->placeholder('选择状态')
            ->width(110);
        });

        $table->model()->orderBy('id','desc')->paginate(request('pageSize',10));

        return $table;
    }

    /**
     * 表单页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function form()
    {
        $id = request('id');

        $form = new Form(new Config);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->labelCol(['span' => 4])->title($title);
        
        $form->id('id','ID');

        $form->text('title','标题')
        ->rules(['required','max:20'],['required'=>'标题必须填写','max'=>'标题不能超过20个字符']);

        $options = [
            'text'=>'输入框',
            'textarea'=>'文本域',
            'picture'=>'图片',
            'file'=>'文件',
            'switch'=>'开关'
        ];

        $form->select('type','表单类型')
        ->options($options)
        ->default('text');

        $form->text('name','名称')
        ->rules(['required','max:255'],['required'=>'名称必须填写','max'=>'名称不能超过255个字符'])
        ->creationRules(["unique:configs"],['unique'=>'名称已经存在'])
        ->updateRules(["unique:configs,name,{{id}}"],['unique'=>'名称已经存在']);

        $form->text('group_name','分组名称');

        $form->textArea('remark','备注')
        ->rules(['max:255'],['max'=>'备注不能超过255个字符']);

        $form->switch('status','状态')->options([
            'on'  => '正常',
            'off' => '禁用'
        ])->default(true);

        return $form;
    }
}
