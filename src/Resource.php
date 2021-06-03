<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;
    use PerformsQueries;
    use PerformsValidation;
    use ResolvesFields;
    use ResolvesActions;
    use ResolvesFilters;
    use ResolvesSearchs;

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = false;

    /**
     * 模型
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
        return static::$perPage;
    }

    /**
     * 获取表格数据
     *
     * @param  Request $request
     * @return array
     */
    protected function getData($request)
    {
        $query = $this->buildIndexQuery($request, static::newModel(), $this->searches($request), $this->fieldFilters($request));

        if(static::pagination()) {
            $result = $query->paginate(request('pageSize', static::pagination()));
        } else {
            $result = $query->get()->toArray();
        }

        return $result;
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
        $searches = $this->searches($request);

        $table = Table::key('table')
        ->title($this->title)
        ->toolBar(false)
        ->columns($this->indexFields($request))
        ->batchActions([]);
        
        $table->search(function($search) use ($searches, $request) {
            foreach ($searches as $key => $value) {
                $item = $search->item($value->column, $value->name)->operator($value->operator);
                switch ($value->type) {
                    case 'select':
                        $item->select($value->options($request));
                        break;
                    case 'multipleSelect':
                        $item->multipleSelect($value->options($request));
                        break;
                    case 'datetime':
                        $item->datetime();
                        break;
                    case 'date':
                        $item->date();
                        break;
                    default:
                        # code...
                        break;
                }
            }
        });

        if(static::pagination()) {
            $table = $table->pagination(
                $data->currentPage(),
                $data->perPage(),
                $data->total()
            )->datasource($data->items());
        } else {
            $table = $table->datasource($data);
        }

        return $this->setLayoutContent($table);
    }
}
