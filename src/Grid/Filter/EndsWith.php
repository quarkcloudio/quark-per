<?php

namespace QuarkCMS\QuarkAdmin\Grid\Filter;

class EndsWith extends Like
{
    protected $exprFormat = '%{value}';
}
