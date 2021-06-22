<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;

trait ResolvesFields
{
    /**
     * 列表页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function indexFields(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->isShownOnIndex()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 创建页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function creationFields(Request $request)
    {
        foreach ($this->fields($request) as $value) {
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
        foreach ($this->fields($request) as $value) {
            if($value->isShownOnUpdate()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 详情页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function detailFields(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->isShownOnDetail()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 列表页表格列
     *
     * @param  Request  $request
     * @return array
     */
    public function columns(Request $request)
    {
        foreach ($this->indexFields($request) as $value) {
            $columns[] = $this->buildTableColumn($value);
        }

        $tableRowActions = $this->tableRowActions($request);

        if(!empty($tableRowActions)) {
            $columns[] = Column::make('actions','操作')
            ->valueType('option')
            ->actions($tableRowActions)
            ->render();
        }

        return $columns ?? [];
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

        switch ($field->type) {
            case 'select':
                $column = $column->valueType($field->type)->valueEnum($field->getValueEnum());
                break;

            case 'radio':
                $column = $column->valueType($field->type)->valueEnum($field->getValueEnum());
                break;

            case 'switch':
                $column = $column->valueType('select')->valueEnum($field->getValueEnum());
                break;

            default:
                $column = $column->valueType($field->type);
                break;
        }

        if ($field->editable) {
            $column = $column->editable($field->type, $field->options ?? []);
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
