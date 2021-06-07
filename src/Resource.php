<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Table;

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
     * 工具栏
     *
     * @param  ToolBar $toolBar
     * @return array
     */
    public function toolBar($request, ToolBar $toolBar)
    {
        return $toolBar::make($this->title . '列表')->actions($this->indexActions($request));
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
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function index(Request $request)
    {
        $data = $this->getIndexData($request);

        $table = Table::key('table')
        ->title($this->title . '列表')
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
}
