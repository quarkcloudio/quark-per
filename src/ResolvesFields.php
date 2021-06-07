<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;

trait ResolvesFields
{
    /**
     * 列表页表格列
     *
     * @param  Request  $request
     * @return array
     */
    public function indexFields(Request $request)
    {
        foreach ($this->fields($request) as $key => $value) {
            if($value->showOnIndex) {
                $columns[] = $this->buildTableColumn($value);
            }
        }

        return $columns ?? [];
    }

    /**
     * 详情字段
     *
     * @param  Request  $request
     * @return array
     */
    public function detailFields(Request $request)
    {
        foreach ($this->fields($request) as $key => $value) {
            if($value->showOnDetail) {

            }
        }
    }

    /**
     * 创建表格的列
     *
     * @param  object  $field
     * @return array
     */
    protected function buildTableColumn($field)
    {
        $column = Column::make($field->name, $field->label);

        if(in_array($field->type, ['select', 'radio'])) {
            if($field->options) {
                foreach ($field->options as $optionKey => $optionValue) {
                    $options[$optionValue['value']] = $optionValue['label'];
                }
                
                $column = $column->valueEnum($options);
            }
        }

        if ($field->type === 'switch') {
            foreach ($field->options as $optionKey => $optionValue) {
                $valueKey = ($optionKey === 'on') ? 1 : 0;
                $options[$valueKey] = $optionValue;
            }

            $column = $column->valueEnum($options);
        }

        return $column->render();
    }

    /**
     * 定义字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [];
    }
}
