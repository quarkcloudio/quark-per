<?php

namespace QuarkCMS\QuarkAdmin\Grid\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Form;
use Exception;

class Modal
{
    public  $title,
            $width,
            $okText,
            $cancelText;

    function __construct() {
        $this->form = new Form;
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

    // render
    public function render()
    {
        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/index','/action',$action);

        $this->form->setAction($action);
        $this->modal['form'] = $this->form->render();
        return $this->modal;
    }
}
