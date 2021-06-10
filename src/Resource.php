<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;
    use PerformsActions;
    use PerformsQueries;
    use PerformsValidation;
    use ResolvesFields;
    use ResolvesActions;
    use ResolvesFilters;
    use ResolvesSearches;

    /**
     * 页面标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 模型
     *
     * @var string
     */
    public static $model = null;

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = false;

    /**
     * 创建表单的接口
     *
     * @var string
     */
    public static $creationApi = null;

    /**
     * 页面标题
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * 模型
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 配置分页
     *
     * @return mixed
     */
    public static function pagination()
    {
        return static::$perPage;
    }

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
     * 工具栏
     *
     * @param  ToolBar $toolBar
     * @return array
     */
    public function toolBar($request, ToolBar $toolBar)
    {
        return $toolBar::make($this->title() . '列表')->actions($this->indexActions($request));
    }

    /**
     * 获取表格数据
     *
     * @param  Request $request
     * @return array|object
     */
    protected function getIndexData($request)
    {
        $query = $this->buildIndexQuery($request, static::newModel(), $this->searches($request), $this->filters($request));

        if(static::pagination()) {
            $result = $query->paginate(request('pageSize', static::pagination()));
        } else {
            $result = $query->get()->toArray();
        }

        return $result;
    }

    /**
     * 列表页资源
     *
     * @param  void
     * @return array
     */
    public function index(Request $request)
    {
        $data = $this->getIndexData($request);

        $table = Table::key('table')
        ->title($this->title() . '列表')
        ->toolBar($this->toolBar($request, new ToolBar))
        ->columns($this->indexFields($request))
        ->batchActions($this->tableAlertActions($request))
        ->searches($this->indexSearches($request));

        if(static::pagination()) {
            $table = $table->pagination(
                $data->currentPage(),
                $data->perPage(),
                $data->total()
            )->datasource($data->items());
        } else {
            $table = $table->datasource($data);
        }

        return $this->setLayoutContent($table);
    }

    /**
     * 执行行为
     *
     * @param  void
     * @return array
     */
    public function action(Request $request, $uriKey)
    {
        return $this->handleRequest($request, static::newModel(), $uriKey, $this->actions($request));
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
     * 创建页面显示前回调
     * 
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function beforeCreating(Request $request, $data)
    {
        return $data;
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
     * @param object $model
     * @return array|object
     */
    public function afterSaved(Request $request, $model)
    {
        return $model;
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
        ->actions($actions);

        $card = Card::title('创建' . $this->title())->headerBordered()->extra($extra)->body($form);

        return $this->setLayoutContent($card);
    }
}
