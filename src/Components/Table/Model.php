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
     * @var array
     */
    protected $data = [];

    /**
     * Create a new table model instance.
     *
     * @param EloquentModel $model
     */
    public function __construct(EloquentModel $model = null)
    {
        $this->model = $model;

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
     * @throws \Exception
     *
     * @return Collection
     */
    protected function get()
    {
        // search todo
        $search = request('search');
        $filter = request('filter');
        $sorter = request('sorter');

        if(!empty($filter)) {
            foreach ($filter as $filterKey => $filterValue) {
                if(!empty($filterValue)) {
                    $this->model = $this->model->whereIn($filterKey,$filterValue);
                }
            }
        }

        if(!empty($sorter)) {
            foreach ($sorter as $sorterKey => $sorterValue) {
                if($sorterValue === 'ascend' || $sorterValue === 'descend') {
                    if($sorterValue === 'descend') {
                        $orderBy = 'desc';
                    } else {
                        $orderBy = 'asc';
                    }
                    $this->model = $this->model->orderBy($sorterKey,$orderBy);
                }
            }
        } else {
            $this->model = $this->model->orderBy('id','desc');
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
