<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class ResolvesForm.
 */
trait ResolvesForm
{
    /**
     * 创建表单的接口
     *
     * @var string
     */
    public static $creationApi = null;

    /**
     * 编辑表单的接口
     *
     * @var string
     */
    public static $updateApi = null;

    /**
     * 创建表单的接口
     *
     * @return string
     */
    public static function creationApi()
    {
        return static::$creationApi ?? Str::replaceLast('/create', '/store', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }

    /**
     * 更新表单的接口
     *
     * @return string
     */
    public static function updateApi()
    {
        return static::$updateApi ?? Str::replaceLast('/edit', '/save', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }

    /**
     * 右上角自定义区域
     *
     * @param  void
     * @return array
     */
    public function formExtra()
    {
        return Action::make('返回上一页')->showStyle('link')->actionType('back');
    }

    /**
     * 获取表单按钮
     *
     * @param  void
     * @return array
     */
    public function formActions()
    {
        return [
            Action::make('重置')->actionType('reset'),
            Action::make("提交")->showStyle('primary')->actionType('submit'),
            Action::make('返回上一页')->actionType('back')
        ];
    }

    /**
     * 创建页资源
     *
     * @param  void
     * @return array
     */
    public function create(Request $request)
    {
        // 表单提交接口
        $creationApi = $this->creationApi();

        // 右上角自定义区域
        $extra = $this->formExtra();

        // 获取表单项
        $items = $this->creationFields($request);

        // 获取表单按钮
        $actions = $this->formActions();

        // 表格
        $form = Form::api($creationApi)
        ->style(['marginTop' => '30px'])
        ->items($items)
        ->actions($actions)
        ->initialValues($this->beforeCreating($request));

        $card = Card::title('创建' . $this->title())->headerBordered()->extra($extra)->body($form);

        return $this->setLayoutContent($card);
    }

    /**
     * 编辑页资源
     *
     * @param  void
     * @return array
     */
    public function edit(Request $request)
    {
        $data = static::newModel()->where('id', $request->id)->first()->toArray();

        // 表单提交接口
        $updateApi = $this->updateApi();

        // 右上角自定义区域
        $extra = $this->formExtra();

        // 获取表单项
        $items = $this->updateFields($request);

        // 获取表单按钮
        $actions = $this->formActions();

        // 表格
        $form = Form::api($updateApi)
        ->style(['marginTop' => '30px'])
        ->items($items)
        ->actions($actions)
        ->initialValues($this->beforeEditing($request, $data));

        $card = Card::title('编辑' . $this->title())->headerBordered()->extra($extra)->body($form);

        return $this->setLayoutContent($card);
    }

    /**
     * 创建页面显示前回调
     * 
     * @param Request $request
     * @return array
     */
    public function beforeCreating(Request $request)
    {
        return [];
    }

    /**
     * 编辑页面显示前回调
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function beforeEditing(Request $request, $data)
    {
        return $data;
    }

    /**
     * 保存数据前回调
     *
     * @param Request $request
     * @param array $submitData
     * @return array
     */
    public function beforeSaving(Request $request, $submitData)
    {
        return $submitData;
    }

    /**
     * 保存数据后回调
     *
     * @param Request $request
     * @param mixed $model
     * @return array|object
     */
    public function afterSaved(Request $request, $model)
    {
        return $model;
    }
}
