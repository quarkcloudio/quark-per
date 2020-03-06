<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use QuarkCMS\QuarkAdmin\Grid\Column;
use QuarkCMS\QuarkAdmin\Grid\Search;
use QuarkCMS\QuarkAdmin\Grid\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class Grid
{
    /**
     * The grid data model instance.
     *
     * @var \QuarkCMS\QuarkAdmin\Grid\Model|\Illuminate\Database\Eloquent\Builder
     */
    protected $model;

    /**
     * The grid search.
     *
     * @var
     */
    protected $search;

    /**
     * The grid advancedSearch.
     *
     * @var
     */
    protected $advancedSearch;

    /**
     * Collection of all grid columns.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $columns;

    /**
     * All variables in grid view.
     *
     * @var array
     */
    protected $table = [];

    /**
     * The grid data.
     *
     * @var
     */
    protected $data;

    /**
     * Create a new grid instance.
     *
     * @param Eloquent $model
     */
    public function __construct(Eloquent $model)
    {
        $this->model = new Model($model, $this);
        $this->columns = Collection::make();
        $this->search = new Search;
        $this->advancedSearch = new Search;
    }

    /**
     * Add a column to Grid.
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    public function column($name, $label = '')
    {
        return $this->__call($name, array_filter([$label]));
    }

    /**
     * Get Grid model.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Paginate the grid.
     *
     * @param int $perPage
     *
     * @return void
     */
    public function paginate($perPage = 10)
    {
        $this->model()->setPerPage($perPage);
    }

    /**
     * parseOperator
     *
     * @param $items
     * @param $inputs
     *
     * @return void
     */
    protected function parseOperator($items,$inputs)
    {
        foreach ($items as $key => $item) {
            if(isset($inputs[$item->name])) {
                switch ($item->operator) {
                    case 'equal':
                        $this->model->where($item->name,$inputs[$item->name]);
                        break; 

                    case 'like':
                        $this->model->where($item->name,'like','%'.$inputs[$item->name].'%');
                        break;

                    case 'gt':
                        $this->model->where($item->name,'>',$inputs[$item->name]);
                        break;

                    case 'lt':
                        $this->model->where($item->name,'<',$inputs[$item->name]);
                        break;

                    case 'between':
                        $this->model->whereBetween($item->name, [$inputs[$item->name][0], $inputs[$item->name][1]]);
                        break;

                    case 'in':
                        $this->model->whereIn($item->name, $inputs[$item->name]);
                        break;

                    case 'notIn':
                        $this->model->whereNotIn($item->name, $inputs[$item->name]);
                        break;

                    default:
                        $this->model->where($item->name,$inputs[$item->name]);
                        break;
                }
            }
        }
    }

    /**
     * @return array|Collection|mixed
     */
    protected function applyQuery()
    {
        if(request()->has('search')) {
            $searchInputs = request('search');

            // 普通搜索
            $searchRender = $this->search->render();

            // 高级搜索
            $advancedSearchRender = $this->advancedSearch->render();

            $items = Arr::collapse([$searchRender['items'], $advancedSearchRender['items']]);

            // 解析操作符
            $this->parseOperator($items,$searchInputs);
        }

        if (method_exists($this->model->eloquent(), 'paginate')) {
            $this->model->usePaginate(true);

            return $this->model->buildData(false);
        }

        return $this->model->buildData(false);
    }

    /**
     * Dynamically add columns to the grid view.
     *
     * @param $method
     * @param $arguments
     *
     * @return Column
     */
    public function __call($method, $arguments)
    {
        $label = $arguments[0] ?? null;
        $column = new Column($method, $label);

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * Set grid title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->table['title'] = $title;

        return $this;
    }

    /**
     * 搜索
     *
     * @return bool
     */
    public function search(Closure $callback = null)
    {
        $callback($this->search);
    }

    /**
     * 高级搜索
     *
     * @return bool
     */
    public function advancedSearch(Closure $callback = null)
    {
        $callback($this->advancedSearch);
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function render()
    {
        // 普通搜索
        $this->table['search'] = $this->search->render();

        // 高级搜索
        $this->table['advancedSearch'] = $this->advancedSearch->render();

        // 表格数据
        $this->data = $this->applyQuery();

        $this->columns->map(function (Column $column) {

            $getColumn['title'] = $column->label;
            $getColumn['dataIndex'] = $column->name;
            $getColumn['key'] = $column->name;
            $getColumn['width'] = $column->width;
            $getColumn['using'] = $column->using;
            $getColumn['tag'] = $column->tag;
            $getColumn['link'] = $column->link;
            $getColumn['image'] = $column->image;
            $getColumn['qrcode'] = $column->qrcode;
            $this->table['columns'][] = $getColumn;

            if (Str::contains($column->name, '.')) {
                list($relation, $relationColumn) = explode('.', $column->name);
                foreach ($this->data as $key => $value) {
                    $value[$column->name] = '';
                    if($value[$relation]) {
                        $value[$column->name] = $value[$relation]->$relationColumn;
                    }
                    $this->data[$key] = $value;
                }
            }
        });

        $this->table['dataSource'] = $this->data->toArray();

        $model = $this->model()->eloquent();
        // 表格分页
        $pagination['defaultCurrent'] = 1;
        $pagination['current'] = $model->currentPage();
        $pagination['pageSize'] = $model->perPage();
        $pagination['total'] = $model->total();
        $this->table['pagination'] = $pagination;

        $data['table'] = $this->table;
        return $data;
    }
}
