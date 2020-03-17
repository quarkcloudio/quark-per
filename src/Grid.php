<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use QuarkCMS\QuarkAdmin\Grid\Column;
use QuarkCMS\QuarkAdmin\Grid\Search;
use QuarkCMS\QuarkAdmin\Grid\Actions;
use QuarkCMS\QuarkAdmin\Grid\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Validator;

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
     * The grid actions.
     *
     * @var
     */
    protected $actions;

    /**
     * The grid batchActions.
     *
     * @var
     */
    protected $batchActions;

    /**
     * The grid extendActions.
     *
     * @var
     */
    protected $extendActions;

    /**
     * The grid rowActions.
     *
     * @var
     */
    protected $rowActions;

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
        $this->actions = new Actions;
        $this->batchActions = new Actions;
        $this->extendActions = new Actions;
        $this->rowActions = new Actions;
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
            if(isset($inputs[$item->name]) || (isset($inputs[$item->name.'_start']) && isset($inputs[$item->name.'_end']))) {
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
                        if($item->component == 'input') {
                            $this->model->whereBetween($item->name, [$inputs[$item->name.'_start'], $inputs[$item->name.'_end']]);
                        } else {
                            $this->model->whereBetween($item->name, [$inputs[$item->name][0], $inputs[$item->name][1]]);
                        }
                        break;

                    case 'in':
                        $this->model->whereIn($item->name, $inputs[$item->name]);
                        break;

                    case 'notIn':
                        $this->model->whereNotIn($item->name, $inputs[$item->name]);
                        break;

                    case 'scope':
                        foreach ($item->options as $optionKey => $option) {
                            if($option['value'] == $inputs[$item->name]) {
                                if(isset($option['method'])) {
                                    foreach ($option['method'] as $methodKey => $method) {
                                        $methodName = array_key_first($method);
                                        $params = $method[$methodName];
                                        $this->model->$methodName(...$params);
                                    }
                                }
                            }
                        }
                        break;

                    case 'where':
                        foreach ($item->methods as $methodKey => $method) {
                            $methodName = array_key_first($method);
                            $params = $method[$methodName];
                            $params = json_decode(str_replace('{input}', $inputs[$item->name], json_encode($params)),true);
                            $this->model->$methodName(...$params);
                        }
                        break;

                    case 'group':
                        foreach ($item->options as $optionKey => $option) {
                            $operator = $inputs[$item->name.'_start'];
                            $value = $inputs[$item->name.'_end'];
                            switch ($operator) {
                                case 'equal':
                                    $this->model->where($item->name,$value);
                                    break;

                                case 'notEqual':
                                    $this->model->where($item->name,'<>',$value);
                                    break;

                                case 'gt':
                                    $this->model->where($item->name,'>',$value);
                                    break;

                                case 'lt':
                                    $this->model->where($item->name,'<',$value);
                                    break;

                                case 'nlt':
                                    $this->model->where($item->name,'>=',$value);
                                    break;

                                case 'ngt':
                                    $this->model->where($item->name,'<=',$value);
                                    break;

                                case 'like':
                                    $this->model->where($item->name,'like','%'.$value.'%');
                                    break;

                                default:

                                    break;
                            }
                        }
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
    protected function query()
    {
        if(request()->has('search')) {
            $searchInputs = request('search');
            // 搜索
            $searchRender = $this->search->render();
            // 解析操作符
            $this->parseOperator($searchRender['items'],$searchInputs);
        }

        if(request()->has('sorter')) {
            $sorterInputs = request('sorter');
            if(isset($sorterInputs['order'])) {
                $sorterInputs['order'] == 'descend' ? $order = 'desc' : $order='asc';
                $this->model->orderBy($sorterInputs['field'],$order);
            }
        } else {
            $this->model->orderBy('id','desc');
        }

        if(request()->has('filters')) {
            $filterInputs = request('filters');
            foreach ($filterInputs as $filterKey => $filterValue) {
                if(isset($filterValue)) {
                    foreach ($filterValue as $key => $value) {
                        if($key == 0) {
                            $this->model->where($filterKey,$value);
                        } else {
                            $this->model->orWhere($filterKey,$value);
                        }
                    }
                }
            }
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

        return $this->search;
    }

    /**
     * action自动验证
     *
     * @return bool
     */
    public function actionValidator($id,$data,$items)
    {
        if(is_array($id)) {
            foreach ($id as $key => $value) {
                foreach ($items as $itemKey => $item) {
                    if($item->rules) {
        
                        foreach ($item->rules as &$rule) {
                            if (is_string($rule)) {
                                $rule = str_replace('{{id}}', $value, $rule);
                            }
                        }
        
                        $rules[$item->name] = $item->rules;
                        $validator = Validator::make($data,$rules,$item->ruleMessages);
                        if ($validator->fails()) {
        
                            $errors = $validator->errors()->getMessages();
                            foreach($errors as $errorKey => $error) {
                                $errorMsg = $error[0];
                            }
        
                            return $errorMsg;
                        }
                    }
                }
            }
        } else {
            foreach ($items as $itemKey => $item) {
                if($item->rules) {
    
                    foreach ($item->rules as &$rule) {
                        if (is_string($rule)) {
                            $rule = str_replace('{{id}}', $id, $rule);
                        }
                    }
    
                    $rules[$item->name] = $item->rules;
                    $validator = Validator::make($data,$rules,$item->ruleMessages);
                    if ($validator->fails()) {
    
                        $errors = $validator->errors()->getMessages();
                        foreach($errors as $errorKey => $error) {
                            $errorMsg = $error[0];
                        }
    
                        return $errorMsg;
                    }
                }
            }
        }
    }

    /**
     * 列表所有action的数据操作
     *
     * @return bool
     */
    public function action()
    {
        $data = request()->all();

        $id = $data['id'];
        $actionName = $data['actionName'];

        if(empty($id)) {
            return Helper::error('请选择数据！');
        }

        // 删除不必要的字段
        unset($data['actionName']);
        unset($data['actionUrl']);
        unset($data['id']);

        $model = $this->model()->eloquent();

        if(is_array($id)) {
            $query = $model->whereIn('id',$id);
        } else {
            $query = $model->where('id',$id);
        }

        if($actionName == 'editable') {
            $query = $query->update($data);
        } else {
            if(!empty($this->actions->items)) {
                foreach ($this->actions->items as $key => $value) {
                    if($value->name == $actionName) {
                        if(isset($value->modal['form']['items'])) {
                            $errorMsg = $this->actionValidator($id,$data,$value->modal['form']['items']);
                            if($errorMsg) {
                                return Helper::error($errorMsg);
                            }
                    
                            $query = $query->update($data);
                        }
    
                        if(!empty($value->model->methods)) {
                            foreach ($value->model->methods as $methodKey => $method) {
                                $methodName = array_key_first($method);
                                $params = $method[$methodName];
        
                                $query = $query->$methodName(...$params);
                            }
                        }
                    }
                }
            }
    
            if(!empty($this->batchActions->items)) {
                foreach ($this->batchActions->items as $batchKey => $batchValue) {
                    if($batchValue->name == $actionName) {
                        if(isset($batchValue->modal['form']['items'])) {
                            $errorMsg = $this->actionValidator($id,$data,$batchValue->modal['form']['items']);
    
                            if($errorMsg) {
                                return Helper::error($errorMsg);
                            }
                    
                            $query = $query->update($data);
                        }
    
                        if(!empty($batchValue->model->methods)) {
                            foreach ($batchValue->model->methods as $methodKey => $method) {
                                $methodName = array_key_first($method);
                                $params = $method[$methodName];
        
                                $query = $query->$methodName(...$params);
                            }
                        }
                    }
                }
            }

            foreach ($this->columns as $columnKey => $column) {
                if($column->rowActions) {
                    if(!empty($column->rowActions->items)) {
                        foreach ($column->rowActions->items as $rowhKey => $rowValue) {
                            if($rowValue->name == $actionName) {
                                if(isset($rowValue->modal['form']['items'])) {
                                    $errorMsg = $this->actionValidator($id,$data,$rowValue->modal['form']['items']);
                                    if($errorMsg) {
                                        return Helper::error($errorMsg);
                                    }
                            
                                    $query = $query->update($data);
                                }
            
                                if(!empty($rowValue->model->methods)) {
                                    foreach ($rowValue->model->methods as $methodKey => $method) {
                                        $methodName = array_key_first($method);
                                        $params = $method[$methodName];
                
                                        $query = $query->$methodName(...$params);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if($query) {
            return Helper::success('操作成功！');
        } else {
            return Helper::error('操作失败！');
        }
    }

    /**
     * actions
     *
     * @return bool
     */
    public function actions(Closure $callback = null)
    {
        $this->actions->prefix('action');

        $callback($this->actions);

        return $this->actions;
    }

    /**
     * batchActions
     *
     * @return bool
     */
    public function batchActions(Closure $callback = null)
    {
        $this->batchActions->prefix('batchAction');

        $callback($this->batchActions);

        return $this->batchActions;
    }

    /**
     * extendActions
     *
     * @return bool
     */
    public function extendActions(Closure $callback = null)
    {
        $this->extendActions->prefix('extendAction');

        $callback($this->extendActions);

        return $this->extendActions;
    }

    /**
     * rowActions
     *
     * @return bool
     */
    public function rowActions(Closure $callback = null)
    {
        $this->rowActions->prefix('rowAction');

        $callback($this->rowActions);

        return $this->rowActions;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function render()
    {
        // 头部操作
        $this->table['actions'] = $this->actions->render();

        // 批量操作
        $this->table['batchActions'] = $this->batchActions->render();

        // 扩展操作
        $this->table['extendActions'] = $this->extendActions->render();

        // 搜索
        $this->table['search'] = $this->search->render();

        // 表格数据
        $this->data = $this->query();

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
            $getColumn['editable'] = $column->editable;

            if($column->filters) {
                $getColumn['filters'] = $column->filters;
            }

            if($column->rowActions) {
                $getColumn['rowActions'] = $column->rowActions->render();
            }

            $getColumn['sorter'] = $column->sorter;
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
