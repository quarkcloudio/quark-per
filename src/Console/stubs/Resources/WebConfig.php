<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;
use QuarkCMS\Quark\Facades\TabPane;

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
     * 表单接口
     *
     * @param  Request  $request
     * @return string
     */
    public function formApi($request)
    {
        return (new \App\Admin\Actions\ChangeWebConfig)->api();
    }

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $groupNames = $this->newModel()
        ->where('status', 1)
        ->distinct()
        ->pluck('group_name');

        foreach ($groupNames as $groupName) {

            $configs = $this->newModel()
            ->where('status', 1)
            ->where('group_name',$groupName)
            ->get()
            ->toArray();
            
            $fields = [];

            foreach ($configs as $config) {
                switch ($config['type']) {
                    case 'text':
                        $fields[] = Field::text($config['name'],$config['title'])
                        ->extra($config['remark']);
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

                        $fields[] = Field::file($config['name'],$config['title'])
                        ->extra($config['remark'])
                        ->button('上传'.$config['title']);
                        break;

                    case 'textarea':
                        $fields[] = Field::textArea($config['name'],$config['title'])
                        ->extra($config['remark']);
                        break;

                    case 'switch':
                        $fields[] = Field::switch($config['name'],$config['title'])
                        ->extra($config['remark'])
                        ->trueValue('正常')
                        ->falseValue('禁用');
                        break;

                    case 'picture':
                        $image = null;
                        if($config['value']) {
                            $image['id'] = $config['value'];
                            $image['name'] = get_picture($config['value'],0,'name');
                            $image['size'] = get_picture($config['value'],0,'size');
                            $image['url'] = get_picture($config['value'],0,'path');
                        }

                        $fields[] = Field::image($config['name'],$config['title'])
                        ->extra($config['remark'])
                        ->button('上传'.$config['title']);
                        break;

                    default:
                        $fields[] = Field::text($config['name'],$config['title'])
                        ->extra($config['remark']);
                        break;
                }
            }

            $tabPanes[] = TabPane::make($groupName, $fields);
        }

        return $tabPanes;
    }

    /**
     * 表单显示前回调
     * 
     * @param Request $request
     * @return array
     */
    public function beforeCreating(Request $request)
    {
        $configs = $this->newModel()
        ->where('status', 1)
        ->get();

        foreach ($configs as $value) {
            $data[$value->name] = $value->value;
        }

        return $data ?? [];
    }

    /**
     * 注册行为，注册后才能被资源调用
     *
     * @param  Request  $request
     * @return object
     */
    public function actions(Request $request)
    {
        return [
            new \App\Admin\Actions\ChangeWebConfig
        ];
    }
}