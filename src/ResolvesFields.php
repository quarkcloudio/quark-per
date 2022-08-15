<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Descriptions;
use QuarkCMS\Quark\Facades\DescriptionField;

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
     * 包裹在组件内的详情页字段
     *
     * @param  Request  $request
     * @return array
     */
    public function detailFieldsWithinComponents(Request $request, $data = [])
    {
        $componentType = 'description';

        foreach ($this->fields($request) as $value) {
            if($value->component === 'tabPane') {
                $componentType = 'tabPane';
                $subItems = [];
                foreach ($value->body as $subValue) {
                    if($subValue->isShownOnDetail()) {
                        $subItems[] = $subValue->transformToColumn();
                    }
                }

                $descriptions = Descriptions::style(['padding' => '24px'])
                ->title(false)
                ->column(self::$detailColumn)
                ->columns($subItems)
                ->dataSource($data)
                ->actions($this->detailActions($request));

                $value->body = $descriptions;
                $items[] = $value;
            } else {
                if($value->isShownOnDetail()) {
                    $items[] = $value->transformToColumn();
                }
            }
        }

        if($componentType === 'description') {
            $items = Descriptions::style(['padding' => '24px'])
            ->title(false)
            ->column(self::$detailColumn)
            ->columns($items)
            ->dataSource($data)
            ->actions($this->detailActions($request));
        }

        return $items;
    }

    /**
     * 列表页表格列
     *
     * @param  Request  $request
     * @return array
     */
    public function indexColumns(Request $request)
    {
        foreach ($this->indexFields($request) as $value) {
            $columns[] = $value->transformToColumn();
        }

        $indexTableRowActions = $this->indexTableRowActions($request);

        if(!empty($indexTableRowActions)) {
            $columns[] = Column::make('actions','操作')
            ->valueType('option')
            ->actions($indexTableRowActions)
            ->fixed('right')
            ->render();
        }

        return $columns ?? [];
    }

    /**
     * 导出字段
     *
     * @param  Request  $request
     * @return array
     */
    public function exportFields(Request $request)
    {
        foreach ($this->getFields($request) as $value) {
            if($value->isShownOnExport()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 导入字段
     *
     * @param  Request  $request
     * @return array
     */
    public function importFields(Request $request)
    {
        foreach ($this->getFields($request) as $value) {
            if($value->isShownOnImport()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 不包含Hidden组件内字段的导入字段
     *
     * @param  Request  $request
     * @return array
     */
    public function importFieldsWithoutHidden(Request $request)
    {
        foreach ($this->getFields($request) as $value) {
            if($value->isShownOnImport() && $value->component != 'hiddenField') {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 不包含When组件内字段的导入字段
     *
     * @param  Request  $request
     * @return array
     */
    public function importFieldsWithoutWhen(Request $request)
    {
        foreach ($this->getFieldsWithoutWhen($request) as $value) {
            if($value->isShownOnImport()) {
                $items[] = $value;
            }
        }

        return $items ?? [];
    }

    /**
     * 获取字段
     *
     * @param  Request  $request
     * @return array
     */
    public function getFields(Request $request)
    {
        return $this->findFields($this->fields($request));
    }

    /**
     * 获取不包含When组件的字段
     *
     * @param  Request  $request
     * @return array
     */
    public function getFieldsWithoutWhen(Request $request)
    {
        return $this->findFields($this->fields($request), false);
    }

    /**
     * 查找字段
     *
     * @param  array  $fields
     * @param  bool  $when
     * @return array
     */
    public function findFields($fields,$when = true)
    {
        $items = [];

        foreach ($fields as $value) {
            if(isset($value->body)) {
                $getItems = $this->findFields($value->body);
                $items = array_merge($items, $getItems);
            } else {
                if (strpos($value->component,'Field') !== false) { 
                    $items[] = $value;

                    // 是否获取when组件中的字段
                    if ($when) {
                        $whenFields = $this->getWhenFields($value);
                        if($whenFields) {
                            $items = array_merge($items, $whenFields);
                        }
                    }
                }
            }
        }

        return $items;
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
                    $items = array_merge($items,$this->findFields($body));
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
