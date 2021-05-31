<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $pagination = false;

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 配置分页
     *
     * @return mixed
     */
    public static function pagination()
    {
        return static::$pagination;
    }

    /**
     * 将字段转换表格列
     *
     * @param  Request $request
     * @return array
     */
    protected function fieldsToColumns($request)
    {
        $fields = $this->fields($request);
        $columns = [];

        foreach ($fields as $key => $value) {
            if($value->showOnIndex) {
                $column = Column::make($value->name, $value->label);

                if(isset($value->options)) {
                    $options = [];
                    if(in_array($value->type, ['checkbox', 'radio', 'select'])) {
                        foreach ($value->options as $optionKey => $optionValue) {
                            $options[$optionValue['value']] = $optionValue['label'];
                        }
                    } else {
                        $options = $value->options;
                    }
                    $column->valueEnum($options);
                }

                if($value->type === 'datetime') {
                    $columns[] = Column::make($value->name.'_range', $value->label)->hideInTable()->valueType('dateTimeRange')->render();
                    $column->hideInSearch()->valueType('dateTime');
                }

                if($value->type === 'date') {
                    $columns[] = Column::make($value->name.'_range', $value->label)->hideInTable()->valueType('dateRange')->render();
                    $column->hideInSearch()->valueType('date');
                }

                $columns[] = $column->render();
            }
        }

        return $columns;
    }

    /**
     * 获取表格数据
     *
     * @param  Request $request
     * @return array
     */
    protected function getData($request)
    {
        $model = static::newModel();

        if(static::pagination()) {
            return $model->paginate(request('pageSize',static::pagination()));
        } else {
            return $model->get()->toArray();
        }
    }

    /**
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function indexResource(Request $request)
    {
        $data = $this->getData($request);

        $table = Table::key('table')
        ->title($this->title)
        ->toolBar(false)
        ->columns($this->fieldsToColumns($request))
        ->batchActions([]);

        if(static::pagination()) {
            $table = $table->datasource($data->items())->pagination($data->currentPage(), $data->perPage(), $data->total());
        } else {
            $table = $table->datasource($data);
        }

        return $this->setLayoutContent($table);
    }
}
