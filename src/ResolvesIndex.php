<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Table;

/**
 * Class ResolvesIndex.
 */
trait ResolvesIndex
{
    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = false;

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
     * @param  Request $request
     * @return array
     */
    public function toolBar($request)
    {
        return ToolBar::make($this->title() . '列表')->actions($this->indexActions($request));
    }

    /**
     * 获取表格数据
     *
     * @param  Request $request
     * @return array|object
     */
    protected function getIndexData($request)
    {
        $query = $this->buildIndexQuery($request, $this->model(), $this->searches($request), $this->filters($request));

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
        ->toolBar($this->toolBar($request))
        ->columns($this->columns($request))
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
}
