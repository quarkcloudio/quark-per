<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;

trait ResolvesFields
{
    /**
     * 首页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function indexFields(Request $request)
    {
        $fields = $this->fields($request);
        $columns = [];

        foreach ($fields as $key => $value) {
            if($value->showOnIndex) {
                $columns[] = Column::make($value->name, $value->label);
            }
        }

        return $columns;
    }

    /**
     * 详情字段
     *
     * @param  Request  $request
     * @return array
     */
    public function detailFields(NovaRequest $request)
    {

    }

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [];
    }
}
