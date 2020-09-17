<?php

namespace QuarkCMS\QuarkAdmin;

use JsonSerializable;
use Illuminate\Support\Str;

abstract class Element implements JsonSerializable
{
    /**
     * The element's unique key.
     *
     * @var string
     */
    public $key = '';

    /**
     * The element's component.
     *
     * @var string
     */
    public $component;

    /**
     * The element's style.
     *
     * @var array
     */
    public $style = [];

    /**
     * Create a new element.
     *
     * @param  string|null  $component
     * @return void
     */
    public function __construct($component = null)
    {
        $this->component = $component ?? $this->component;
    }

    /**
     * Create a new element.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Get the component key for the element.
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return $this->component;
    }

    /**
     * Set the element style.
     *
     * @return string
     */
    public function style($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key = Str::uuid();

        return array_merge([
            'key' => $this->key(),
            'component' => $this->component(),
            'style' => $this->style
        ]);
    }
}
