<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceActionRequest extends QuarkRequest
{
    /**
     * 执行行为
     *
     * @return array
     */
    public function handleRequest()
    {
        $model = $this->model();

        if($this->input('id')) {
            $model = $model->whereIn('id', explode(',', $this->input('id')));
        }

        foreach ($this->newResource()->actions($this) as $value) {

            if($value->actionType() === 'dropdown') {
                foreach ($value->getActions() as $key => $value) {
                    if($this->route('uriKey') === $value->uriKey()) {
                        return $value->handle($this, $model);
                    }
                }
            }

            if($this->route('uriKey') === $value->uriKey()) {

                return $value->handle($this, $model);
            }
        }
    }
}
