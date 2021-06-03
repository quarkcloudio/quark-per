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
                $column = Column::make($value->name, $value->label);

                if(in_array($value->type, ['select', 'radio'])) {
                    if($value->options) {
                        foreach ($value->options as $optionKey => $optionValue) {
                            $options[$optionValue['value']] = $optionValue['label'];
                        }
                        
                        $column = $column->valueEnum($options);
                    }
                }

                if ($value->type === 'switch') {
                    foreach ($value->options as $optionKey => $optionValue) {
                        $valueKey = ($optionKey === 'on') ? 1 : 0;
                        $options[$valueKey] = $optionValue;
                    }

                    $column = $column->valueEnum($options);
                }

                $columns[] = $column->render();
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
