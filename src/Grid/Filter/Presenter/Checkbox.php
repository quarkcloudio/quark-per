<?php

namespace QuarkCMS\QuarkAdmin\Grid\Filter\Presenter;

use QuarkCMS\QuarkAdmin\Facades\Admin;

class Checkbox extends Radio
{
    protected function prepare()
    {
        $script = "$('.{$this->filter->getId()}').iCheck({checkboxClass:'icheckbox_minimal-blue'});";

        Admin::script($script);
    }
}
