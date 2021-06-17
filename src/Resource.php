<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Resource.
 */
abstract class Resource extends JsonResource
{
    use Layout;
    use PerformsActions;
    use PerformsQueries;
    use PerformsValidation;
    use ResolvesFields;
    use ResolvesActions;
    use ResolvesFilters;
    use ResolvesSearches;
    use ResolvesIndex;
    use ResolvesForm;

    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = null;

    /**
     * 模型
     *
     * @var string
     */
    public static $model = null;

    /**
     * The underlying model resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    public $resource;

    /**
     * 初始化
     *
     * @var mixed
     */
    public function __construct()
    {
        $this->resource = static::newModel();
    }

    /**
     * 页面标题
     *
     * @return string
     */
    public function title()
    {
        return static::$title;
    }

    /**
     * 刷新的模型
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 模型
     *
     * @return mixed
     */
    public function model()
    {
        return $this->resource;
    }
}
