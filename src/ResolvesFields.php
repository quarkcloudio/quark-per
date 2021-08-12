<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;

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
        foreach ($this->getFields($request) as $value) {
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
        foreach ($this->getFields($request) as $value) {
            if($value->isShownOnCreation()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 不包含When组件内字段的创建页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function creationFieldsWithoutWhen(Request $request)
    {
        foreach ($this->getFieldsWithoutWhen($request) as $value) {
            if($value->isShownOnCreation()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 包裹在组件内的创建页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function creationFieldsWithinComponents(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->component === 'tabPane') {
                $subItems = [];
                foreach ($value->body as $subValue) {
                    if($subValue->isShownOnCreation()) {
                        $subItems[] = $subValue;
                    }
                }
                $value->body = $subItems;
                $items[] = $value;
            } else {
                if($value->isShownOnCreation()) {
                    $items[] = $value;
                }
            }
        }

        return $items;
    }

    /**
     * 编辑页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function updateFields(Request $request)
    {
        foreach ($this->getFields($request) as $value) {
            if($value->isShownOnUpdate()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 不包含When组件内字段的编辑页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function updateFieldsWithoutWhen(Request $request)
    {
        foreach ($this->getFieldsWithoutWhen($request) as $value) {
            if($value->isShownOnUpdate()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 包裹在组件内的编辑页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function updateFieldsWithinComponents(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->component === 'tabPane') {
                $subItems = [];
                foreach ($value->body as $subValue) {
                    if($subValue->isShownOnUpdate()) {
                        $subItems[] = $subValue;
                    }
                }
                $value->body = $subItems;
                $items[] = $value;
            } else {
                if($value->isShownOnUpdate()) {
                    $items[] = $value;
                }
            }
        }

        return $items;
    }

    /**
     * 详情页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function detailFields(Request $request)
    {
        foreach ($this->getFields($request) as $value) {
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

        switch ($field->component) {
            case 'textField':
                $column = $column->valueType('text');
                break;

            case 'selectField':
                $column = $column->valueType('select')->valueEnum($field->getValueEnum());
                break;

            case 'radioField':
                $column = $column->valueType('radio')->valueEnum($field->getValueEnum());
                break;

            case 'switchField':
                $column = $column->valueType('select')->valueEnum($field->getValueEnum());
                break;

            case 'imageField':
                $column = $column->valueType('image');
                break;

            default:
                $column = $column->valueType($field->component);
                break;
        }

        if ($field->editable) {
            $column = $column->editable($field->component, $field->options ?? []);
        }

        return $column->render();
    }

    /**
     * 获取字段
     *
     * @param  Request  $request
     * @return array
     */
    public function getFields(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->component === 'tabPane') {
                foreach ($value->body as $subValue) {
                    $items[] = $subValue;
                    $whenFields = $this->getWhenFields($subValue);
                    if($whenFields) {
                        $items = array_merge($items, $whenFields);
                    }
                }
            } else {
                $items[] = $value;
                $whenFields = $this->getWhenFields($value);
                if($whenFields) {
                    $items = array_merge($items, $whenFields);
                }
            }
        }

        return $items ?? [];
    }

    /**
     * 获取不包含When组件的字段
     *
     * @param  Request  $request
     * @return array
     */
    public function getFieldsWithoutWhen(Request $request)
    {
        foreach ($this->fields($request) as $value) {
            if($value->component === 'tabPane') {
                foreach ($value->body as $subValue) {
                    $items[] = $subValue;
                }
            } else {
                $items[] = $value;
            }
        }
        return $items ?? [];
    }

    /**
     * 获取When组件中的字段
     *
     * @param  object  $item
     * @return array
     */
    public function getWhenFields($item)
    {
        // when中的变量
        $items = [];

        if(!empty($item->when)) {
            foreach ($item->when['items'] as $when) {
                $body = $when['body'];
                if(is_array($body)) {
                    $items = array_merge($items, $body);
                } elseif(is_object($body)) {
                    $items[] = $body;
                }
            }
        }

        return $items;
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
