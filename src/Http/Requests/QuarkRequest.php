<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuarkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 获取资源
     *
     * @return mixed
     */
    public function resource()
    {
        $resource = 'App\\Admin\\Resources\\'.ucfirst(request()->route('resource'));

        if(class_exists('Nwidart\\Modules\\Facades\\Module')) {
            $modules = \Nwidart\Modules\Facades\Module::allEnabled();

            foreach ($modules as $module) {
                $moduleName = $module->getName();
                $moduleResource = 'Modules\\'.$moduleName.'\\Admin\\Resources\\'.ucfirst(request()->route('resource'));

                if(class_exists($moduleResource)) {
                    $resource = $moduleResource;
                }
            }
        }

        if(!class_exists($resource)) {
            throw new \Exception("Class { $resource } does not exist.");
        }

        return $resource;
    }

    /**
     * 获取新资源模型
     *
     * @return \QuarkCMS\QuarkAdmin\Resource
     */
    public function newResource()
    {
        $resource = $this->resource();

        return new $resource($this->model());
    }

    /**
     * Get a new query builder for the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->model()->newQuery();
    }

    /**
     * Get a new instance of the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        $resource = $this->resource();

        return $resource::newModel();
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Laravel\Nova\Resource
     */
    public function newResourceWith($model)
    {
        $resource = $this->resource();

        return new $resource($model);
    }
}