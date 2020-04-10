<?php

namespace QuarkCMS\QuarkAdmin\Layout;

class Content
{
    public  $content;

    protected $row;

    function __construct() {
        $this->row = new Row;
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

    public function row($callback = null)
    {
        $callback($this->row);

        $this->content['row'][] = $this->row;
        return $this;
    }
}
