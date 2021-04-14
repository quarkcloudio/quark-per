<?php

namespace QuarkCMS\QuarkAdmin\Components\Table;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model
{
    /**
     * Eloquent model instance of the grid model.
     *
     * @var EloquentModel
     */
    protected $model;

    /**
     * table object.
     *
     * @var object
     */
    protected $table = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Create a new table model instance.
     *
     * @param EloquentModel $model
     */
    public function __construct(EloquentModel $model = null,$table = null)
    {
        $this->model = $model;
        $this->table = $table;
        $this->queries = collect();
    }

    /**
     * Set the model of the grid model.
     *
     * @return EloquentModel
     */
    public function setModel(EloquentModel $model)
    {
        return $this->model = $model;
    }

    /**
     * Get the eloquent model of the grid model.
     *
     * @return EloquentModel
     */
    public function eloquent()
    {
        return $this->model;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function data()
    {
        $this->data = $this->get()->toArray();

        return $this->data;
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
                        $this->model = $this->model->where($item->name,$inputs[$item->name]);
                        break; 

                    case 'like':
                        $this->model = $this->model->where($item->name,'like','%'.$inputs[$item->name].'%');
                        break;

                    case 'gt':
                        $this->model = $this->model->where($item->name,'>',$inputs[$item->name]);
                        break;

                    case 'lt':
                        $this->model = $this->model = $this->model->where($item->name,'<',$inputs[$item->name]);
                        break;

                    case 'between':
                        if($item->component == 'input') {
                            $this->model = $this->model->whereBetween($item->name, [$inputs[$item->name.'_start'], $inputs[$item->name.'_end']]);
                        } else {
                            $this->model = $this->model->whereBetween($item->name, [$inputs[$item->name][0], $inputs[$item->name][1]]);
                        }
                        break;

                    case 'in':
                        $this->model = $this->model->whereIn($item->name, $inputs[$item->name]);
                        break;

                    case 'notIn':
                        $this->model = $this->model->whereNotIn($item->name, $inputs[$item->name]);
                        break;

                    case 'scope':
                        foreach ($item->options as $optionKey => $option) {
                            if($option['value'] == $inputs[$item->name]) {
                                if(isset($option['method'])) {
                                    foreach ($option['method'] as $methodKey => $method) {
                                        $methodName = array_key_first($method);
                                        $params = $method[$methodName];
                                        $this->model = $this->model->$methodName(...$params);
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
                            $this->model = $this->model->$methodName(...$params);
                        }
                        break;

                    case 'group':
                        foreach ($item->options as $optionKey => $option) {
                            if(isset($inputs[$item->name.'_start']) && $inputs[$item->name.'_end']) {
                                $operator = $inputs[$item->name.'_start'];
                                $value = $inputs[$item->name.'_end'];
                                switch ($operator) {
                                    case 'equal':
                                        $this->model = $this->model->where($item->name,$value);
                                        break;
    
                                    case 'notEqual':
                                        $this->model = $this->model->where($item->name,'<>',$value);
                                        break;
    
                                    case 'gt':
                                        $this->model = $this->model->where($item->name,'>',$value);
                                        break;
    
                                    case 'lt':
                                        $this->model = $this->model->where($item->name,'<',$value);
                                        break;
    
                                    case 'nlt':
                                        $this->model = $this->model->where($item->name,'>=',$value);
                                        break;
    
                                    case 'ngt':
                                        $this->model = $this->model->where($item->name,'<=',$value);
                                        break;
    
                                    case 'like':
                                        $this->model = $this->model->where($item->name,'like','%'.$value.'%');
                                        break;
    
                                    default:
    
                                        break;
                                }
                            }
                        }
                        break;

                    default:
                        $this->model = $this->model->where($item->name,$inputs[$item->name]);
                        break;
                }
            }
        }
    }

    /**
     * @throws \Exception
     *
     * @return Collection
     */
    public function get()
    {
        if(request()->has('search')) {
            $searchInputs = request('search');
            // 搜索
            $search = $this->table->search->jsonSerialize();
            // 解析操作符
            $this->parseOperator($search['items'],$searchInputs);
        }

        if(request()->has('filter')) {
            $filterInputs = request('filter');
            if(is_array($filterInputs)) {
                // 过滤
                foreach ($filterInputs as $filterKey => $filterValue) {
                    if(!empty($filterValue)) {
                        $this->model = $this->model->whereIn($filterKey,$filterValue);
                    }
                }
            }
        }

        if(request()->has('sorter')) {
            $sorterInputs = request('sorter');
            if(is_array($sorterInputs)) {
                // 排序
                foreach ($sorterInputs as $sorterKey => $sorterValue) {
                    if($sorterValue === 'ascend' || $sorterValue === 'descend') {
                        if($sorterValue === 'descend') {
                            $orderBy = 'desc';
                        } else {
                            $orderBy = 'asc';
                        }
                        $this->model = $this->model->orderBy($sorterKey,$orderBy);
                    }
                }
            }
        }

        $this->queries->unique()->each(function ($query) {
            $this->model = call_user_func_array([$this->model, $query['method']], $query['arguments']);
        });

        return $this->model;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->queries->push([
            'method'    => $method,
            'arguments' => $arguments,
        ]);

        return $this;
    }
}
