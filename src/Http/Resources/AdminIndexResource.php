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
        $columns[] = $column::attribute('avatar')->title('头像')->render();
        $columns[] = $column::attribute('username')->title('用户名')->render();
        $columns[] = $column::attribute('nickname')->title('昵称')->render();
        $columns[] = $column::attribute('email')->title('邮箱')->render();
        $columns[] = $column::attribute('sex')->title('性别')->valueEnum(['1'=>'男','2'=>'女'])->render();
        $columns[] = $column::attribute('phone')->title('手机号')->render();
        $columns[] = $column::attribute('last_login_time')->title('最后登录时间')->valueType('dateTime')->render();
        $columns[] = $column::attribute('status')->title('状态')->valueEnum(['1'=>'正常','0'=>'禁用'])->width(60)->render();
        $columns[] = $column::attribute('actions')->title('操作')->hideInSearch()->width(60)->render();

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