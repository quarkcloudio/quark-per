<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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
        $resource = 'App\\Admin\\Dashboards\\'.ucfirst(request()->route('dashboard'));

        if(class_exists('Nwidart\\Modules\\Facades\\Module')) {
            $modules = \Nwidart\Modules\Facades\Module::allEnabled();

            foreach ($modules as $module) {
                $moduleName = $module->getName();
                $moduleResource = 'Modules\\'.$moduleName.'\\Admin\\Dashboards\\'.ucfirst(request()->route('dashboard'));

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
     * @return \QuarkCMS\QuarkAdmin\Dashboard
     */
    public function newResource()
    {
        $resource = $this->resource();

        return new $resource();
    }
}