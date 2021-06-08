<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\FormItem;

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
            if($value->isShownOnIndex()) {
                $columns[] = $this->buildTableColumn($value);
            }
        }

        $columns[] = Column::make('actions','操作')->valueType('option')
        ->actions(
            $this->tableRowActions($request)
        )->render();

        return $columns ?? [];
    }

    /**
     * 创建页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function creationFields(Request $request)
    {
        foreach ($this->fields($request) as $key => $value) {
            if($value->isShownOnCreation()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 编辑页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function updateFields(Request $request)
    {
        foreach ($this->fields($request) as $key => $value) {
            if($value->isShownOnUpdate()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
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
            if($value->isShownOnDetail()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
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
