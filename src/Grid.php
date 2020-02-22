<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use QuarkCMS\QuarkAdmin\Grid\Column;
use QuarkCMS\QuarkAdmin\Grid\Concerns;
use QuarkCMS\QuarkAdmin\Grid\Model;
use QuarkCMS\QuarkAdmin\Grid\Row;
use QuarkCMS\QuarkAdmin\Traits\ShouldSnakeAttributes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Jenssegers\Mongodb\Eloquent\Model as MongodbModel;

class Grid
{
    use ShouldSnakeAttributes,
        Macroable {
            __call as macroCall;
        }

    /**
     * The grid data model instance.
     *
     * @var \QuarkCMS\QuarkAdmin\Grid\Model|\Illuminate\Database\Eloquent\Builder
     */
    protected $model;

    /**
     * Collection of all grid columns.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $columns;

    /**
     * Grid builder.
     *
     * @var \Closure
     */
    protected $builder;

    /**
     * All variables in grid view.
     *
     * @var array
     */
    protected $table = [];

    /**
     * Initialization closure array.
     *
     * @var []Closure
     */
    protected static $initCallbacks = [];

    /**
     * Create a new grid instance.
     *
     * @param Eloquent $model
     * @param Closure  $builder
     */
    public function __construct(Eloquent $model, Closure $builder = null)
    {
        $this->model = new Model($model, $this);
        $this->builder = $builder;

        $this->initialize();

        $this->callInitCallbacks();
    }

    /**
     * Initialize.
     */
    protected function initialize()
    {
        $this->columns = Collection::make();
    }

    /**
     * Initialize with user pre-defined default disables and exporter, etc.
     *
     * @param Closure $callback
     */
    public static function init(Closure $callback = null)
    {
        static::$initCallbacks[] = $callback;
    }

    /**
     * Call the initialization closure array in sequence.
     */
    protected function callInitCallbacks()
    {
        if (empty(static::$initCallbacks)) {
            return;
        }

        foreach (static::$initCallbacks as $callback) {
            call_user_func($callback, $this);
        }
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
        if (Str::contains($name, '.')) {
            return $this->addRelationColumn($name, $label);
        }

        if (Str::contains($name, '->')) {
            return $this->addJsonColumn($name, $label);
        }

        return $this->__call($name, array_filter([$label]));
    }

    /**
     * Batch add column to grid.
     *
     * @example
     * 1.$grid->columns(['name' => 'Name', 'email' => 'Email' ...]);
     * 2.$grid->columns('name', 'email' ...)
     *
     * @param array $columns
     *
     * @return Collection|null
     */
    public function columns($columns = [])
    {
        if (func_num_args() == 0) {
            return $this->columns;
        }

        if (func_num_args() == 1 && is_array($columns)) {
            foreach ($columns as $column => $label) {
                $this->column($column, $label);
            }

            return;
        }

        foreach (func_get_args() as $column) {
            $this->column($column);
        }
    }

    /**
     * Add column to grid.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    protected function addColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setGrid($this);

        return tap($column, function ($value) {
            $this->columns->push($value);
        });
    }

    /**
     * Add a relation column to grid.
     *
     * @param string $name
     * @param string $label
     *
     * @return $this|bool|Column
     */
    protected function addRelationColumn($name, $label = '')
    {
        list($relation, $column) = explode('.', $name);

        $model = $this->model()->eloquent();

        if (!method_exists($model, $relation) || !$model->{$relation}() instanceof Relations\Relation) {
            $class = get_class($model);

            admin_error("Call to undefined relationship [{$relation}] on model [{$class}].");

            return $this;
        }

        $name = ($this->shouldSnakeAttributes() ? Str::snake($relation) : $relation).'.'.$column;

        $this->model()->with($relation);

        return $this->addColumn($name, $label)->setRelation($relation, $column);
    }

    /**
     * Add a json type column to grid.
     *
     * @param string $name
     * @param string $label
     *
     * @return Column
     */
    protected function addJsonColumn($name, $label = '')
    {
        $column = substr($name, strrpos($name, '->') + 2);

        $name = str_replace('->', '.', $name);

        return $this->addColumn($name, $label ?: ucfirst($column));
    }

    /**
     * Prepend column to grid.
     *
     * @param string $column
     * @param string $label
     *
     * @return Column
     */
    protected function prependColumn($column = '', $label = '')
    {
        $column = new Column($column, $label);
        $column->setGrid($this);

        return tap($column, function ($value) {
            $this->columns->prepend($value);
        });
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
     * Apply column search to grid query.
     *
     * @return void
     */
    protected function applyColumnSearch()
    {
        $this->columns->each->bindSearchQuery($this->model());
    }

    /**
     * @return array|Collection|mixed
     */
    protected function applyQuery()
    {
        $this->applyColumnSearch();

        if (method_exists($this->model->eloquent(), 'paginate')) {
            $this->model->usePaginate(true);

            return $this->model->buildData(false);
        }

        return $this->model->buildData(false);
    }

    /**
     * Handle get mutator column for grid.
     *
     * @param string $method
     * @param string $label
     *
     * @return bool|Column
     */
    protected function handleGetMutatorColumn($method, $label)
    {
        if ($this->model()->eloquent()->hasGetMutator($method)) {
            return $this->addColumn($method, $label);
        }

        return false;
    }

    /**
     * Handle relation column for grid.
     *
     * @param string $method
     * @param string $label
     *
     * @return bool|Column
     */
    protected function handleRelationColumn($method, $label)
    {
        $model = $this->model()->eloquent();

        if (!method_exists($model, $method)) {
            return false;
        }

        if (!($relation = $model->$method()) instanceof Relations\Relation) {
            return false;
        }

        if ($relation instanceof Relations\HasOne ||
            $relation instanceof Relations\BelongsTo ||
            $relation instanceof Relations\MorphOne
        ) {
            $this->model()->with($method);

            return $this->addColumn($method, $label)->setRelation(
                $this->shouldSnakeAttributes() ? Str::snake($method) : $method
            );
        }

        if ($relation instanceof Relations\HasMany
            || $relation instanceof Relations\BelongsToMany
            || $relation instanceof Relations\MorphToMany
            || $relation instanceof Relations\HasManyThrough
        ) {
            $this->model()->with($method);

            return $this->addColumn($this->shouldSnakeAttributes() ? Str::snake($method) : $method, $label);
        }

        return false;
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
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        $label = $arguments[0] ?? null;

        if ($this->model()->eloquent() instanceof MongodbModel) {
            return $this->addColumn($method, $label);
        }

        if ($column = $this->handleGetMutatorColumn($method, $label)) {
            return $column;
        }

        if ($column = $this->handleRelationColumn($method, $label)) {
            return $column;
        }

        return $this->addColumn($method, $label);
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
     * Set relation for grid.
     *
     * @param Relations\Relation $relation
     *
     * @return $this
     */
    public function setRelation(Relations\Relation $relation)
    {
        $this->model()->setRelation($relation);

        return $this;
    }

    /**
     * Get the string contents of the grid view.
     *
     * @return string
     */
    public function render()
    {
        $collection = $this->applyQuery();

        $this->columns->map(function (Column $column) {
            $getColumn['title'] = $column->getLabel();
            $getColumn['dataIndex'] = $column->getName();
            $getColumn['key'] = $column->getName();
            $getColumn['width'] = $column->width;
            $this->table['columns'][] = $getColumn;
        });

        $model = $this->model()->eloquent();

        $this->table['dataSource'] = $collection->toArray();

        // 默认页码
        $pagination['defaultCurrent'] = 1;
        // 当前页码
        $pagination['current'] = $model->currentPage();
        // 分页数量
        $pagination['pageSize'] = $model->perPage();
        // 总数量
        $pagination['total'] = $model->total();

        $this->table['pagination'] = $pagination;

        $data['table'] = $this->table;

        return $data;
    }
}
