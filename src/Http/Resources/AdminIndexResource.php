<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\QuarkAdmin\Http\Resources\TableResource;

class AdminIndexResource extends TableResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员列表';

    /**
     * 表格列
     *
     * @param  Column  $column
     * @return array
     */
    public function column(Column $column)
    {
        $columns[] = $column::attribute('username')->title('用户名')->render();
        $columns[] = $column::attribute('nickname')->title('昵称')->render();
        $columns[] = $column::attribute('status')->title('状态')->render();

        return $columns;
    }

    /**
     * 表格数据
     *
     * @param  void
     * @return array
     */
    public function datasource()
    {
        return $this->data->items();
    }

    /**
     * 分页
     *
     * @param  void
     * @return array
     */
    public function pagination()
    {
        $pagination['current'] = $this->data->currentPage();
        $pagination['pageSize'] = $this->data->perPage();
        $pagination['total'] = $this->data->total();

        return $pagination;
    }
}