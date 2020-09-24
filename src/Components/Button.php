<?php

namespace QuarkCMS\QuarkAdmin\Components;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\Button as BaseButton;

class Button extends Element
{
    use BaseButton;

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this));

        return array_merge([
            'name' => $this->name
        ], parent::jsonSerialize());
    }
}
