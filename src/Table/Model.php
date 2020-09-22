<?php

namespace QuarkCMS\QuarkAdmin\Table;

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
    public function __construct(EloquentModel $model)
    {
        $this->model = $model;

        $this->queries = collect();
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
