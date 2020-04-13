<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Content
{
    public  $content;

    function __construct() {
        $this->content['title'] = false;
        $this->content['subTitle'] = false;
        $this->content['description'] = false;
        $this->content['breadcrumb'] = null;
        $this->content['body'] = null;
    }

    public function title($title)
    {
        $this->content['title'] = $title;
        return $this;
    }

    public function subTitle($subTitle)
    {
        $this->content['subTitle'] = $subTitle;
        return $this;
    }

    public function description($description)
    {
        $this->content['description'] = $description;
        return $this;
    }

    public function breadcrumb($breadcrumb)
    {
        $this->content['breadcrumb'] = $breadcrumb;
        return $this;
    }

    public function body($body)
    {
        $this->content['body'] = $body;
        return $this;
    }

    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'row' => Row::class,
    ];

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

            $argument = Arr::get($arguments, 0, ''); //[0];
            $element = new $className($argument);

            $this->content['body']['component']['name'] = $method;

            $this->content['body']['component']['items'][] = $element;

            return $element;
        }
    }
}
