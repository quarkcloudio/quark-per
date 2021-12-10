<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceEditableRequest extends QuarkRequest
{
    /**
     * 执行行为
     *
     * @return array
     */
    public function handleRequest()
    {
        $data = $this->all();

        $edit = [];
        foreach ($data as $key => $value) {
            if($value === 'true' || $value === 'false') {
                $edit[$key] = $value === 'true' ? 1 : 0;
            }
        }

        return $this->model()->where('id', $this->input('id'))->update($edit);
    }
}
