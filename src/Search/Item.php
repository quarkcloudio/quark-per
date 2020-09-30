<?php

namespace QuarkCMS\QuarkAdmin\Search;

use Exception;

class Item
{
    public  $value,
            $defaultValue,
            $label,
            $name,
            $rules,
            $style,
            $component,
            $operator,
            $placeholder;

    /**
     * 控件样式
     *
     * @param  array $style
     * @return $this
     */
    public function style($style = [])
    {
        $this->style = $style;
        return $this;
    }

    /**
     * label 标签的文本
     *
     * @param  string $label
     * @return $this
     */
    public function label($label = '')
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 字段名，支持数组
     *
     * @param  string $name
     * @return $this
     */
    public function name($name = '')
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 校验规则，设置字段的校验逻辑
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function rules($rules,$messages = null)
    {
        $this->rules = $rules;
        $this->ruleMessages = $messages;
        return $this;
    }

    /**
     * 设置保存值。
     *
     * @param  array|string
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置默认值。
     *
     * @param  array|string
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * placeholder
     *
     * @param  string $placeholder
     * @return object
     */
    public function placeholder($placeholder)
    {
        if($this->operator == 'between') {
            if(!is_array($placeholder)) {
                throw new Exception("argument must be an array!");
            }
        }

        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 控件宽度
     * 
     * @param  number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $style['width'] = $value;
        $this->style = $style;
        return $this;
    }

    /**
     * select
     *
     * @param  array $options
     * @return object
     */
    public function select($options)
    {
        $this->component = 'select';

        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        $this->placeholder = '请选择'.$this->label;
        return $this;
    }

    /**
     * multipleSelect
     *
     * @param  array $options
     * @return object
     */
    public function multipleSelect($options)
    {
        $this->component = 'multipleSelect';

        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        $this->placeholder = '请选择'.$this->label;
        return $this;
    }

    /**
     * datetime
     *
     * @param  string $options
     * @return object
     */
    public function datetime($options = [])
    {
        $this->component = 'datetime';
        $this->options = $options;

        return $this;
    }

    protected function unsetNullProperty()
    {
        foreach ($this as $key => $value) {
            if(empty($value)) {
                unset($this->$key);
            }
        }
    }
}
