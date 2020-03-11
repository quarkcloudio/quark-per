<?php

namespace QuarkCMS\QuarkAdmin\Grid\Actions;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Form;
use Exception;

class Add
{
    public  $label,
            $name,
            $placeholder,
            $actionType,
            $type,
            $icon,
            $url,
            $modal;

    public $form;

    function __construct($name,$label = '') {
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->type = 'default';
        $this->placeholder = '请输入'.$this->label;
        $this->form = new Form;
    }

    public function create($url = null)
    {
        $this->url = $url;
        $this->actionType = 'create';
        return $this;
    }

    public function refresh()
    {
        $this->actionType = 'refresh';
        return $this;
    }

    public function link($url = null)
    {
        $this->actionType = 'default';
        $this->url = $url;
        return $this;
    }

    // modal标题
    public function title($title = null)
    {
        $this->modal['title'] = $title;
        return $this;
    }

    // modal确认按钮文字
    public function okText($okText = null)
    {
        $this->modal['okText'] = $okText;
        return $this;
    }

    // modal取消按钮文字
    public function cancelText($cancelText = null)
    {
        $this->modal['cancelText'] = $cancelText;
        return $this;
    }

    // modal宽度
    public function width($width = null)
    {
        $this->modal['width'] = $width;
        return $this;
    }

    public function modal($title,$callback = null) {
        $this->actionType = 'modal';
        $this->modal['title'] = $title;
        $callback($this);

        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/index','/action',$action);

        $this->form->setAction();

        $formRender = $this->form->render();
        $this->modal['form'] = $formRender['form'];
        return $this;
    }

    public function icon($icon = null)
    {
        $this->icon = $icon;
        return $this;
    }

    public function type($type = 'default')
    {
        $this->type = $type;
        return $this;
    }
}
