<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;
    use PerformsQueries;
    use PerformsValidation;
    use ResolvesFields;
    use ResolvesActions;
    use ResolvesFilters;
    use ResolvesSearches;

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
     * 获取表格数据
     *
     * @param  Request $request
     * @return array
     */
    protected function getData($request)
    {
        $query = $this->buildIndexQuery($request, static::newModel(), $this->searches($request), $this->fieldFilters($request));

        if(static::pagination()) {
            $result = $query->paginate(request('pageSize', static::pagination()));
        } else {
            $result = $query->get()->toArray();
        }

        return $result;
    }

    /**
     * 工具栏
     *
     * @param  ToolBar $toolBar
     * @return array
     */
    public function toolBar($request, ToolBar $toolBar)
    {
        return $toolBar::make($this->title)->actions($this->indexActions($request));
    }

    /**
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function indexResource(Request $request)
    {
        $data = $this->getData($request);

        $table = Table::key('table')
        ->title($this->title)
        ->toolBar($this->toolBar($request, new ToolBar))
        ->columns($this->indexFields($request))
        ->batchActions([])
        ->searches($this->resolveSearches($request));

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
}
