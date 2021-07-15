<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class ChangeWebConfig extends Action
{
    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $model
     * @return mixed
     */
    public function handle($fields, $model)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        if(!is_writable($envPath)) {
            return error('操作失败，请检查.env文件是否具有写入权限');
        }

        $result = true;

        // 遍历插入数据
        foreach ($fields->all() as $key => $value) {

            // 修改时清空缓存
            cache($key, null);
            $config = $model->where('name',$key)->first();

            if($config['name'] == 'APP_DEBUG') {
                modify_env([
                    'APP_DEBUG' => $value ? 'true' : 'false'
                ]);
            }

            if($config['type'] == 'file' || $config['type'] == 'picture') {
                if(isset($value['id'])) {
                    $value = $value['id'];
                }
            }

            $updateResult = $model->where('name',$key)->update([
                'value'=>$value
            ]);

            if($updateResult === false) {
                $result = false;
            }
        }

        return $result ? success('操作成功！','reload') : error('操作失败，请重试！');
    }
}