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
        foreach ($this->fields($request) as $key => $value) {
            if($value->showOnIndex) {
                $columns[] = $this->columnBuilder($value);
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
    public function detailFields(NovaRequest $request)
    {

    }

    /**
     * 列创建器
     *
     * @param  object  $field
     * @return array
     */
    protected function columnBuilder($field)
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
