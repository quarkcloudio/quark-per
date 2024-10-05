<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Element;
use QuarkCloudIO\Quark\Component\Form\Form;
use QuarkCloudIO\Quark\Facades\Column;
use Exception;

class Item extends Element
{
    /**
     * 会在 label 旁增加一个 icon，悬浮后展示配置的信息
     *
     * @var string
     */
    public $tooltip = null;

    /**
     * Field 的长度，我们归纳了常用的 Field 长度以及适合的场景，支持了一些枚举 "xs" , "s" , "m" , "l" , "x"
     *
     * @var string|number
     */
    public $width = null;

    /**
     * 配合 label 属性使用，表示是否显示 label 后面的冒号
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 设置保存值。
     *
     * @var bool|string|number|array
     */
    public $value = null;

    /**
     * 设置默认值。
     *
     * @var bool|string|number|array
     */
    public $defaultValue = null;

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @var string
     */
    public $extra = null;

    /**
     * 配合 validateStatus 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @var string
     */
    public $hasFeedback;

    /**
     * 额外的提示信息。
     *
     * @var string
     */
    public $help = null;

    /**
     * 为 true 时不带样式，作为纯字段控件使用
     *
     * @var bool
     */
    public $noStyle = false;

    /**
     * label 标签的文本
     *
     * @var string
     */
    public $label = null;

    /**
     * 标签文本对齐方式,left | right
     *
     * @var string
     */
    public $labelAlign = 'right';

    /**
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}。
     * 你可以通过 Form 的 labelCol 进行统一设置。当和 Form 同时设置时，以 Item 为准
     *
     * @var array
     */
    public $labelCol = [];

    /**
     * 字段名，支持数组
     *
     * @var string|array
     */
    public $name = null;

    /**
     * 是否必填，如不设置，则会根据校验规则自动生成
     *
     * @var bool
     */
    public $required = false;

    /**
     * 控件是否禁用
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * 是否忽略保存到数据库
     *
     * @var bool
     */
    public $ignore = false;

    /**
     * 校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $rules = [];

    /**
     * 校验规则的提示信息
     *
     * @var array
     */
    public $ruleMessages = [];

    /**
     * 创建的校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $creationRules = [];

    /**
     * 创建的校验规则提示信息
     *
     * @var array
     */
    public $creationRuleMessages = [];

    /**
     * 更新的校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $updateRules = [];

    /**
     * 更新的校验规则提示信息
     *
     * @var array
     */
    public $updateRuleMessages = [];

    /**
     * 前台的校验规则
     *
     * @var array
     */
    public $frontendRules = null;

    /**
     * 子节点的值的属性，如 Switch 的是 'checked'
     *
     * @var string
     */
    public $valuePropName = null;

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol。
     * 你可以通过 Form 的 wrapperCol 进行统一设置。当和 Form 同时设置时，以 Item 为准。
     *
     * @var array
     */
    public $wrapperCol = null;

    /**
     * 表单联动
     *
     * @var array
     */
    public $when = null;

    /**
     * 是否在列表页展示
     *
     * @var \Closure|bool
     */
    public $showOnIndex = true;

    /**
     * 是否在详情页展示
     *
     * @var \Closure|bool
     */
    public $showOnDetail = true;

    /**
     * 是否在创建页展示
     *
     * @var \Closure|bool
     */
    public $showOnCreation = true;

    /**
     * 是否在编辑页展示
     *
     * @var \Closure|bool
     */
    public $showOnUpdate = true;

    /**
     * 是否为导出字段
     *
     * @var \Closure|bool
     */
    public $showOnExport = true;

    /**
     * 是否为导入字段
     *
     * @var \Closure|bool
     */
    public $showOnImport = true;

    /**
     * 初始化回调
     *
     * @var mixed
     */
    public $callback;

    /**
     * 可编辑列
     * 
     * @var array|bool
     */
    public $editable = false;

    /**
     * 透传表格列的属性
     * 
     * @var array
     */
    public $column;

    /**
     * 初始化组件
     *
     * @param  string  $name
     * @param  string  $label
     * @param  mixed  $callback
     * @return void
     */
    public function __construct($name, $label = null, $callback = null)
    {
        $this->name = $name;
        $this->label = $label ? $label : $name;
        $this->callback = $callback;
        $this->column = Column::make($this->name, $this->label);
    }

    /**
     * 会在 label 旁增加一个 icon，悬浮后展示配置的信息
     *
     * @param  string $tooltip
     * @return $this
     */
    public function tooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * Field 的长度，我们归纳了常用的 Field 长度以及适合的场景，支持了一些枚举 "xs" , "s" , "m" , "l" , "x"
     *
     * @param  string|number $width
     * @return $this
     */
    public function width($width)
    {
        // if(!in_array($width,['xs','s','m','l','x'])) {
        //     throw new Exception("argument must be 'xs','s','m','l','x'!");
        // }

        $this->width = $width;
        $this->style['width'] = $width;
        return $this;
    }

    /**
     * 配合 label 属性使用，表示是否显示 label 后面的冒号
     *
     * @param  bool $colon
     * @return $this
     */
    public function colon($colon = true)
    {
        $colon ? $this->colon = true : $this->colon = false;
        return $this;
    }

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @param  string $extra
     * @return $this
     */
    public function extra($extra = '')
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * 配合 validateStatus 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @param  bool $hasFeedback
     * @return $this
     */
    public function hasFeedback($hasFeedback = true)
    {
        $hasFeedback ? $this->hasFeedback = true : $this->hasFeedback = false;
        return $this;
    }

    /**
     * 配合 help 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @param  bool $help
     * @return $this
     */
    public function help($help = '')
    {
        $this->help = $help;
        return $this;
    }

    /**
     * 为 true 时不带样式，作为纯字段控件使用
     *
     * @return $this
     */
    public function noStyle()
    {
        $this->noStyle = true;
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
     * 标签文本对齐方式
     *
     * @param  left|right $align
     * @return $this
     */
    public function labelAlign($align = 'right')
    {
        if(!in_array($align,['left','right'])) {
            throw new Exception("argument must be 'left' or 'right'!");
        }

        $this->labelAlign = $align;
        return $this;
    }

    /**
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}。
     * 你可以通过 Form 的 labelCol 进行统一设置。当和 Form 同时设置时，以 Item 为准
     *
     * @param  array|$this $col
     * @return $this
     */
    public function labelCol($col)
    {
        if(!is_array($col)) {
            throw new Exception("argument must be an array!");
        }

        $this->labelCol = $col;
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
     * 是否必填，如不设置，则会根据校验规则自动生成
     * 
     * @return $this
     */
    public function required()
    {
        $this->required = true;
        return $this;
    }

    /**
     * 解析成前端验证规则
     *
     * @param array $rules
     * @return array
     */
    protected function parseFrontendRules($rules,$messages)
    {
        $result = false;

        foreach ($rules as $key => $value) {

            if(strpos($value,':') !== false) {
                $arr = explode(':',$value);
                $rule = $arr[0];
            } else {
                $rule = $value;
            }

            $data = false;

            switch ($rule) {
                case 'required':
                    // 必填
                    $data['required'] = true;
                    $data['message'] = $messages['required'];
                    break;

                case 'min':
                    // 最小字符串数
                    $data['min'] =  (int)$arr[1];
                    $data['message'] = $messages['min'];
                    break;

                case 'max':
                    // 最大字符串数
                    $data['max'] =  (int)$arr[1];
                    $data['message'] = $messages['max'];
                    break;

                case 'email':
                    // 必须为邮箱
                    $data['type'] = 'email';
                    $data['message'] = $messages['email'];
                    break;

                case 'numeric':
                    // 必须为数字
                    $data['type'] = 'number';
                    $data['message'] = $messages['numeric'];
                    break;

                case 'url':
                    // 必须为url
                    $data['type'] = 'url';
                    $data['message'] = $messages['url'];
                    break;

                case 'integer':
                    // 必须为整数
                    $data['type'] = 'integer';
                    $data['message'] = $messages['integer'];
                    break;

                case 'date':
                    // 必须为日期
                    $data['type'] = 'date';
                    $data['message'] = $messages['date'];
                    break;

                case 'boolean':
                    // 必须为布尔值
                    $data['type'] = 'boolean';
                    $data['message'] = $messages['boolean'];
                    break;

                default:
                    $data = false;
                    break;
            }

            if($data) {
                $result[] = $data;
            }
        }

        return $result;
    }

    /**
     * 设置前端验证规则
     *
     * @param void
     *
     * @return void
     */
    protected function setFrontendRules()
    {
        $frontendRules = [];
        $rules = $creationRules = $updateRules = null;

        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        // 判断是否为创建页控制器
        $isCreating = in_array(end($uri), ['create', 'store']);

        // 判断是否为编辑页控制器
        $isEditing = in_array(end($uri), ['edit', 'update']);

        if(!empty($this->rules)) {
            $rules = $this->parseFrontendRules($this->rules,$this->ruleMessages);
        }

        if($isCreating && !empty($this->creationRules)) {
            $creationRules = $this->parseFrontendRules($this->creationRules,$this->creationRuleMessages);
        }

        if($isEditing && !empty($this->updateRules)) {
            $updateRules = $this->parseFrontendRules($this->updateRules,$this->updateRuleMessages);
        }

        if($rules) {
            $frontendRules = array_merge($frontendRules, $rules);
        }

        if($creationRules) {
            $frontendRules = array_merge($frontendRules, $creationRules);
        }

        if($updateRules) {
            $frontendRules = array_merge($frontendRules, $updateRules);
        }

        $this->frontendRules = $frontendRules;
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

        // 设置前端规则
        $this->setFrontendRules();
        return $this;
    }

    /**
     * 校验规则，只在创建表单提交时生效
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function creationRules($rules,$messages = null)
    {
        $this->creationRules = $rules;
        $this->creationRuleMessages = $messages;

        // 设置前端规则
        $this->setFrontendRules();
        return $this;
    }

    /**
     * 校验规则，只在更新表单提交时生效
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function updateRules($rules,$messages = null)
    {
        $this->updateRules = $rules;
        $this->updateRuleMessages = $messages;

        // 设置前端规则
        $this->setFrontendRules();
        return $this;
    }

    /**
     * 子节点的值的属性，如 Switch 的是 'checked'
     * 
     * @param  string $valuePropName
     * @return $this
     */
    public function valuePropName($valuePropName = '')
    {
        $this->valuePropName = $valuePropName;
        return $this;
    }

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol。
     * 你可以通过 Form 的 wrapperCol 进行统一设置。当和 Form 同时设置时，以 Item 为准。
     *
     * @param  array|$this $col
     * @return $this
     */
    public function wrapperCol($col)
    {
        if(!is_array($col)) {
            throw new Exception("argument must be an array!");
        }

        $this->wrapperCol = $col;
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
     * 是否禁用状态，默认为 false
     * 
     * @param  bool $status
     * @return object
     */
    public function disabled($status = true)
    {
        $status ? $this->disabled = true : $this->disabled = false;
        return $this;
    }
    
    /**
     * 是否忽略保存到数据库，默认为 false
     * 
     * @param  bool $status
     * @return object
     */
    public function ignore($status = true)
    {
        $status ? $this->ignore = true : $this->ignore = false;
        return $this;
    }

    /**
     * 表单联动
     * 
     * @param  mix $value
     * @return object
     */
    public function when(...$value)
    {
        if(count($value) == 2) {
            $operator = '=';
            $option = $value[0];
            $whenItem['body'] = $value[1]();
        } elseif(count($value) == 3) {
            $operator = $value[0];
            $option = $value[1];
            $whenItem['body'] = $value[2]();
        }

        switch ($operator) {
            case '=':
                $whenItem['condition'] = "<%=String(" . $this->name . ") === '" . $option . "' %>";
              break;
            case '>':
                $whenItem['condition'] = "<%=String(" . $this->name . ") > '" . $option . "' %>";
              break;
            case '<':
                $whenItem['condition'] = "<%=String(" . $this->name . ") < '" . $option . "' %>";
              break;
            case '<=':
                $whenItem['condition'] = "<%=String(" . $this->name . ") <= '" . $option . "' %>";
              break;
            case '>=':
                $whenItem['condition'] = "<%=String(" . $this->name . ") => '" . $option . "' %>";
              break;
            case 'has':
                $whenItem['condition'] = "<%=(String(" . $this->name . ").indexOf('" . $option . "') !=-1) %>";
              break;
            case 'in':
                $whenItem['condition'] = "<%=(" . json_encode($option) . ".indexOf(" . $this->name . ") !=-1) %>";
              break;
            default:
                $whenItem['condition'] = "<%=String(" . $this->name . ") === '" . $option . "' %>";
              break;
        }

        $whenItem['condition_name'] = $this->name;
        $whenItem['condition_operator'] = $operator;
        $whenItem['condition_option'] = $option;

        $this->whenItem[] = $whenItem;
        $when['component'] = 'when';
        $when['items'] = $this->whenItem;

        $this->when = $when;
        return $this;
    }

    /**
     * Specify that the element should be hidden from the index view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromIndex($callback = true)
    {
        $this->showOnIndex = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the detail view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideFromDetail($callback = true)
    {
        $this->showOnDetail = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the creation view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideWhenCreating($callback = true)
    {
        $this->showOnCreation = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the update view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideWhenUpdating($callback = true)
    {
        $this->showOnUpdate = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the export file.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideWhenExporting($callback = true)
    {
        $this->showOnExport = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the import file.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function hideWhenImporting($callback = true)
    {
        $this->showOnImport = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array($callback, func_get_args());
        }
        : ! $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the index view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnIndex($callback = true)
    {
        $this->showOnIndex = $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the detail view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnDetail($callback = true)
    {
        $this->showOnDetail = $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the creation view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnCreating($callback = true)
    {
        $this->showOnCreation = $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the update view.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnUpdating($callback = true)
    {
        $this->showOnUpdate = $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the export file.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnExporting($callback = true)
    {
        $this->showOnExport = $callback;

        return $this;
    }

    /**
     * Specify that the element should be hidden from the import file.
     *
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function showOnImporting($callback = true)
    {
        $this->showOnImport = $callback;

        return $this;
    }

    /**
     * Specify that the element should only be shown on the index view.
     *
     * @return $this
     */
    public function onlyOnIndex()
    {
        $this->showOnIndex = true;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;
        $this->showOnExport = false;
        $this->showOnImport = false;

        return $this;
    }

    /**
     * Specify that the element should only be shown on the detail view.
     *
     * @return $this
     */
    public function onlyOnDetail()
    {
        $this->showOnIndex = false;
        $this->showOnDetail = true;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;
        $this->showOnExport = false;
        $this->showOnImport = false;

        return $this;
    }

    /**
     * Specify that the element should only be shown on forms.
     *
     * @return $this
     */
    public function onlyOnForms()
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = true;
        $this->showOnUpdate = true;
        $this->showOnExport = false;
        $this->showOnImport = false;

        return $this;
    }

    /**
     * Specify that the element should only be shown on export file.
     *
     * @return $this
     */
    public function onlyOnExport()
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;
        $this->showOnExport = true;
        $this->showOnImport = false;

        return $this;
    }

    /**
     * Specify that the element should only be shown on import file.
     *
     * @return $this
     */
    public function onlyOnImport()
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;
        $this->showOnExport = false;
        $this->showOnImport = true;

        return $this;
    }

    /**
     * Specify that the element should be hidden from forms.
     *
     * @return $this
     */
    public function exceptOnForms()
    {
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;
        $this->showOnExport = true;
        $this->showOnImport = true;

        return $this;
    }


    /**
     * Check for showing when updating.
     *
     * @return bool
     */
    public function isShownOnUpdate(): bool
    {
        return $this->showOnUpdate;
    }

    /**
     * Check showing on index.
     *
     * @return bool
     */
    public function isShownOnIndex(): bool
    {
        return $this->showOnIndex;
    }

    /**
     * Check showing on detail.
     *
     * @return bool
     */
    public function isShownOnDetail(): bool
    {
        return $this->showOnDetail;
    }

    /**
     * Check for showing when creating.
     *
     * @return bool
     */
    public function isShownOnCreation(): bool
    {
        return $this->showOnCreation;
    }

    /**
     * Check for showing when exporting.
     *
     * @return bool
     */
    public function isShownOnExport(): bool
    {
        return $this->showOnExport;
    }

    /**
     * Check for showing when importing.
     *
     * @return bool
     */
    public function isShownOnImport(): bool
    {
        return $this->showOnImport;
    }

    /**
     * 设置为可编辑列
     *
     * @param  bool  $editable
     * @return $this
     */
    public function editable($editable = true)
    {        
        $this->editable = $editable;

        return $this;
    }

    /**
     * 透传表格列的属性
     *
     * @param  mixed  $callback
     * @return void
     */
    public function column($callback)
    {
        $this->column = $callback($this->column);

        return $this;
    }

    /**
     * 表单属性转换为表格列的属性
     *
     * @return void
     */
    public function transformToColumn()
    {
        switch ($this->component) {
            case 'textField':
                $this->column->valueType('text');
                break;

            case 'selectField':
                $this->column->valueType('select')->valueEnum($this->getValueEnum());
                break;

            case 'radioField':
                $this->column->valueType('radio')->valueEnum($this->getValueEnum());
                break;

            case 'switchField':
                $this->column->valueType('select')->valueEnum($this->getValueEnum());
                break;

            case 'imageField':
                $this->column->valueType('image');
                break;

            default:
                $this->column->valueType($this->component);
                break;
        }

        if ($this->editable) {
            $this->column->editable($this->component, $this->options ?? []);
        }

        return $this->column->render();
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->name.$this->label, true);
        }

        return array_merge([
            'label' => $this->label,
            'name' => $this->name,
            'help' => $this->help,
            'extra' => $this->extra,
            'disabled' => $this->disabled,
            'frontendRules' => $this->frontendRules,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue,
            'when' => $this->when,
            'editable' => $this->editable
        ], parent::jsonSerialize());
    }
}
