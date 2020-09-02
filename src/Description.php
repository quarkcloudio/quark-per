<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Closure;

class Show
{
    public $show;

    public $data;

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'field' => Show\Field::class,
    ];

    /**
     * Create a new form instance.
     *
     * @param $data
     * @param \Closure $callback
     */
    public function __construct($data)
    {
        $this->data = $data;
        $layout['labelCol']['span'] = 3;
        $layout['wrapperCol']['span'] = 21;
        $this->show['layout'] = $layout;
    }

    /**
     * show title.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function title($title)
    {
        $this->show['title'] = $title;
        return $this;
    }

    /**
     * show layout.
     *
     * @param string $url
     *
     * @return bool|mixed
     */
    public function layout($layout)
    {
        $this->show['layout'] = $layout;
        return $this;
    }

    /**
     * show disableEdit.
     *
     * @return bool
     */
    public function disableEdit()
    {
        $this->show['disableEdit'] = true;
        return $this;
    }

    /**
     * show disableDelete.
     *
     * @return bool
     */
    public function disableDelete()
    {
        $this->show['disableDelete'] = true;
        return $this;
    }

    /**
     * show disableReturnList.
     *
     * @return bool
     */
    public function disableReturnList()
    {
        $this->show['disableReturnList'] = true;
        return $this;
    }

    /**
     * 渲染前回调
     *
     * @return bool
     */
    public function rendering(Closure $callback = null)
    {
        $callback($this);
    }

    /**
     * Find field class.
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function findFieldClass($method)
    {
        $class = Arr::get(static::$availableFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    public function __call($method, $arguments)
    {
        if ($className = static::findFieldClass($method)) {

            $column = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($column, array_slice($arguments, 1));
            $this->show['items'][] = $element;

            return $element;
        }
    }

    /**
     * 填充数据
     *
     * @param array $rules
     *
     * @return array
     */
    protected function initialValues()
    {
        foreach ($this->show['items'] as $key => $item) {
            $item->value = $this->data[$item->name];
            $this->show['items'][$key] = $item;
        }
    }

    public function render()
    {
        $this->initialValues();
        $data['show'] = $this->show;
        return $data;
    }
}
