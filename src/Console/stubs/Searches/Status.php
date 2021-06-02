<?php

namespace App\Admin\Searches;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Searches\Select;

class Status extends Select
{
    /**
     * 显示名称
     *
     * @var string
     */
    public $name = '状态';

    /**
     * 执行查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('status', ($value === 'on'));
    }

    /**
     * 属性
     *
     * @param  Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'on' => '正常',
            'off' => '禁用'
        ];
    }
}