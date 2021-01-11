<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class Form extends Element
{
    /**
     * 表单标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 表单宽度
     *
     * @var string|number
     */
    public $width = null;

    /**
     * 配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 表单原始数据
     *
     * @var array
     */
    public $values = null;

    /**
     * 解析完之后表单数据
     *
     * @var array
     */
    public $initialValues = null;

    /**
     * 表单提交时的数据
     *
     * @var array
     */
    public $data = null;

    /**
     * label 标签的文本对齐方式,left | right
     *
     * @var string
     */
    public $labelAlign = 'right';

    /**
     * 表单名称，会作为表单字段 id 前缀使用
     *
     * @var string
     */
    public $name = null;

    /**
     * 当字段被删除时保留字段值
     *
     * @var bool
     */
    public $preserve = true;

    /**
     * 必选样式，可以切换为必选或者可选展示样式。此为 Form 配置，Form.Item 无法单独配置
     *
     * @var bool
     */
    public $requiredMark = true;

    /**
     * 提交失败自动滚动到第一个错误字段
     *
     * @var bool
     */
    public $scrollToFirstError = false;

    /**
     * 设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @var string
     */
    public $dateFormatter = 'string';

    /**
     * 表单布局，horizontal|vertical
     *
     * @var string
     */
    public $layout = 'horizontal';

    /**
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}
     *
     * @var array
     */
    public $labelCol = ['span' => 2];

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol
     *
     * @var string
     */
    public $wrapperCol = ['span' => 14];

    /**
     * 表单按钮布局样式
     *
     * @var string
     */
    public $buttonWrapperCol = ['offset' => 2, 'span' => 22 ];

    /**
     * 表格提交的地址
     *
     * @var string
     */
    public $api = null;

    /**
     * 表单项
     *
     * @var array
     */
    public $items = null;

    /**
     * 绑定的模型
     *
     * @var object
     */
    public $model;

    /**
     * eloquent模型
     *
     * @var object
     */
    public $eloquentModel;

    /**
     * 创建页面显示前回调
     *
     * @var object
     */
    public $creatingCallback;

    /**
     * 编辑页面显示前回调
     *
     * @var object
     */
    public $editingCallback;

    /**
     * 数据保存前回调
     *
     * @var object
     */
    public $savingCallback;

    /**
     * 数据保存后回调
     *
     * @var object
     */
    public $savedCallback;

    /**
     * 是否禁用重置按钮
     *
     * @var bool
     */
    public $disabledResetButton = false;

    /**
     * 重置按钮文字展示
     *
     * @var string
     */
    public $resetButtonText = '重置';

    /**
     * 是否禁用提交按钮
     *
     * @var bool
     */
    public $disabledSubmitButton = false;

    /**
     * 提交按钮文字展示
     *
     * @var string
     */
    public $submitButtonText = '提交';

    /**
     * 是否禁用返回按钮
     *
     * @var bool
     */
    public $disabledBackButton = false;

    /**
     * 返回按钮文字展示
     *
     * @var string
     */
    public $backButtonText = '返回上一页';

    /**
     * 表单字段控件
     *
     * @var array
     */
    public static $formFields = [
        'hidden' => Components\Form\Fields\Hidden::class,
        'display' => Components\Form\Fields\Display::class,
        'text' => Components\Form\Fields\Text::class,
        'textarea' => Components\Form\Fields\TextArea::class,
        'textArea' => Components\Form\Fields\TextArea::class,
        'number' => Components\Form\Fields\Number::class,
        'radio' => Components\Form\Fields\Radio::class,
        'image' => Components\Form\Fields\Image::class,
        'file' => Components\Form\Fields\File::class,
        'tree' => Components\Form\Fields\Tree::class,
        'select' => Components\Form\Fields\Select::class,
        'checkbox' => Components\Form\Fields\Checkbox::class,
        'icon' => Components\Form\Fields\Icon::class,
        'switch' => Components\Form\Fields\SwitchField::class,
        'icon' => Components\Form\Fields\Icon::class,
        'date' => Components\Form\Fields\Date::class,
        'dateRange' => Components\Form\Fields\DateRange::class,
        'datetime' => Components\Form\Fields\Datetime::class,
        'datetimeRange' => Components\Form\Fields\DatetimeRange::class,
        'time' => Components\Form\Fields\Time::class,
        'timeRange' => Components\Form\Fields\TimeRange::class,
        'editor' => Components\Form\Fields\Editor::class,
        'map' => Components\Form\Fields\Map::class,
        'cascader' => Components\Form\Fields\Cascader::class,
        'search' => Components\Form\Fields\Search::class,
        'list' => Components\Form\Fields\ListField::class,
    ];

    /**
     * 初始化表单组件
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct(Eloquent $model = null)
    {
        $this->component = 'form';
        $this->model = $model;

        // 初始化表单提交地址
        $this->initApi();

        // 初始化表单提交数据
        $this->initData();

        return $this;
    }

    /**
     * 初始化api
     *
     * @param  void
     * @return void
     */
    protected function initApi()
    {
        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);

        if($this->isCreating()) {
            $action = Str::replaceLast('/create','/store',$action);
        }

        if($this->isEditing()) {
            $action = Str::replaceLast('/edit','/update',$action);
        }

        $this->api($action);
    }

    /**
     * 初始化提交表单数据
     *
     * @param  void
     * @return void
     */
    protected function initData()
    {
        if(Str::endsWith(\request()->route()->getName(), ['/store', '/update'])) {
            $this->data = request()->all();
        }
    }

    /**
     *  配置表单标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     *  配置表单宽度
     *
     * @param  string  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     *  配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @param  bool  $colon
     * @return $this
     */
    public function colon($colon)
    {
        $this->colon = $colon;
        return $this;
    }

    /**
     *  解析initialValue
     *
     * @param  object  $item
     * @return $this
     */
    public function parseInitialValue($item,$initialValues)
    {
        $value = null;
        if(isset($item->name)) {

            if(isset($item->defaultValue)) {
                $value = $item->defaultValue;
            }

            if(isset($initialValues[$item->name])) {
                $value = $initialValues[$item->name];
            }

            if(isset($item->value)) {
                $value = $item->value;
            }

            if(!empty($value)) {
                if(is_string($value)) {
                    if(count(explode('[',$value))>1 || count(explode('{',$value))>1) {
                        $value = json_decode($value, true);
                    }
                }
            }
        }

        return $value;
    }

    /**
     *  表单默认值，只有初始化以及重置时生效
     *
     * @param  array  $initialValues
     * @return $this
     */
    public function initialValues($initialValues = null)
    {
        $data = [];

        if(isset($this->items)) {
            foreach ($this->items as $item) {
                $value = $this->parseInitialValue($item,$initialValues);
                if($value !== null) {
                    $data[$item->name] = $value;
                }

                // when中的变量
                if(!empty($item->when)) {
                    foreach ($item->when as $when) {
                        foreach ($when['items'] as $whenItem) {
                            $whenValue = $this->parseInitialValue($whenItem,$initialValues);
                            if($whenValue !== null) {
                                $data[$whenItem->name] = $whenValue;
                            }
                        }
                    }
                }
            }
        }

        $this->initialValues = $data;
        return $this;
    }

    /**
     *  label 标签的文本对齐方式,left | right
     *
     * @param  string  $labelAlign
     * @return $this
     */
    public function labelAlign($labelAlign)
    {
        $this->labelAlign = $labelAlign;
        return $this;
    }

    /**
     *  表单名称，会作为表单字段 id 前缀使用
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *  当字段被删除时保留字段值
     *
     * @param  bool  $preserve
     * @return $this
     */
    public function preserve($preserve)
    {
        $this->preserve = $preserve;
        return $this;
    }

    /**
     *  必选样式，可以切换为必选或者可选展示样式。此为 Form 配置，Form.Item 无法单独配置
     *
     * @param  bool  $requiredMark
     * @return $this
     */
    public function requiredMark($requiredMark)
    {
        $this->requiredMark = $requiredMark;
        return $this;
    }

    /**
     *  提交失败自动滚动到第一个错误字段
     *
     * @param  bool  $scrollToFirstError
     * @return $this
     */
    public function scrollToFirstError($scrollToFirstError)
    {
        $this->scrollToFirstError = $scrollToFirstError;
        return $this;
    }

    /**
     *  设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
     *
     * @param  string  $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     *  自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @param  string  $dateFormatter
     * @return $this
     */
    public function dateFormatter($dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;
        return $this;
    }

    /**
     *  表单布局，horizontal|vertical
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['horizontal', 'vertical'])) {
            throw new Exception("argument must be in 'horizontal', 'vertical'!");
        }

        if($layout === 'vertical') {
            $this->labelCol = null;
            $this->wrapperCol = null;
            $this->buttonWrapperCol = null;
        }

        $this->layout = $layout;
        return $this;
    }

    /**
     *  label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}
     *
     * @param  array  $labelCol
     * @return $this
     */
    public function labelCol($labelCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set labelCol!");
        }

        $this->labelCol = $labelCol;
        return $this;
    }

    /**
     *  需要为输入控件设置布局样式时，使用该属性，用法同 labelCol
     *
     * @param  array  $wrapperCol
     * @return $this
     */
    public function wrapperCol($wrapperCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set wrapperCol!");
        }

        $this->wrapperCol = $wrapperCol;
        return $this;
    }

    /**
     *  表单按钮布局样式,默认：['offset' => 2, 'span' => 22 ]
     *
     * @param  array  $buttonWrapperCol
     * @return $this
     */
    public function buttonWrapperCol($buttonWrapperCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set buttonWrapperCol!");
        }

        $this->buttonWrapperCol = $buttonWrapperCol;
        return $this;
    }

    /**
     *  表单提交的接口链接
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     *  表单项
     *
     * @param  array  $items
     * @return $this
     */
    public function items($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * 读取模型
     *
     * @return $this
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * 判断是否为创建页面
     *
     * @return bool
     */
    public function isCreating(): bool
    {
        return Str::endsWith(\request()->route()->getName(), ['/create', '/store']);
    }

    /**
     * 判断是否为编辑页面
     *
     * @return bool
     */
    public function isEditing(): bool
    {
        return Str::endsWith(\request()->route()->getName(), '/edit', '/update');
    }

    /**
     * 解析验证提交数据库前的值
     *
     * @param array $data
     * @return array
     */
    public function itemValidator($data,$name,$rules,$ruleMessages)
    {
        $errorMsg = null;
        foreach ($rules as &$rule) {
            if (is_string($rule) && isset($data['id'])) {
                $rule = str_replace('{id}', $data['id'], $rule);
            }
        }
        $getRules[$name] = $rules;
        $validator = Validator::make($data,$getRules,$ruleMessages);
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            foreach($errors as $key => $value) {
                $errorMsg = $value[0];
            }
            return $errorMsg;
        }

        return $errorMsg;
    }

    /**
     * 验证提交数据库前的值
     *
     * @param array $data
     * @return array
     */
    public function validator($data = null)
    {
        $items = $this->items;

        if(empty($data)) {
            $data = $this->data;
        }

        foreach ($items as $key => $value) {

            // 通用验证规则
            if($value->rules) {
                $errorMsg = $this->itemValidator($data,$value->name,$value->rules,$value->ruleMessages);
                if($errorMsg) {
                    return $errorMsg;
                }
            }

            // 新增数据，验证规则
            if($this->isCreating()) {
                if($value->creationRules) {
                    $errorMsg = $this->itemValidator($data,$value->name,$value->creationRules,$value->creationRuleMessages);
                    if($errorMsg) {
                        return $errorMsg;
                    }
                }
            }

            // 编辑数据，验证规则
            if($this->isEditing()) {
                if($value->updateRules) {
                    $errorMsg = $this->itemValidator($data,$value->name,$value->updateRules,$value->updateRuleMessages);
                    if($errorMsg) {
                        return $errorMsg;
                    }
                }
            }

            // when中的变量
            if(!empty($value->when)) {
                foreach ($value->when as $when) {
                    foreach ($when['items'] as $whenItem) {
                        // 通用验证规则
                        if($whenItem->rules) {
                            $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->rules,$whenItem->ruleMessages);
                            if($errorMsg) {
                                return $errorMsg;
                            }
                        }

                        // 新增数据，验证规则
                        if($this->isCreating()) {
                            if($whenItem->creationRules) {
                                $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->creationRules,$whenItem->creationRuleMessages);
                                if($errorMsg) {
                                    return $errorMsg;
                                }
                            }
                        }

                        // 编辑数据，验证规则
                        if($this->isEditing()) {
                            if($whenItem->updateRules) {
                                $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->updateRules,$whenItem->updateRuleMessages);
                                if($errorMsg) {
                                    return $errorMsg;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 解析保存提交数据库前的值
     *
     * @param array $rules
     * @return array
     */
    protected function parseSubmitData($data)
    {
        $items = $this->items;

        foreach ($items as $key => $item) {

            // 删除忽略的值
            if($item->ignore) {
                if(isset($data[$item->name])) {
                    unset($data[$item->name]);
                }
            }

            // when中的变量
            if(!empty($item->when)) {
                foreach ($item->when as $when) {
                    foreach ($when['items'] as $whenItem) {
                        // 删除忽略的值
                        if($whenItem->ignore) {
                            if(isset($data[$whenItem->name])) {
                                unset($data[$whenItem->name]);
                            }
                        }
                    }
                }
            }
        }

        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = json_encode($value);
            }
        }

        return $data;
    }

    /**
     * 表单内置的保存方法
     *
     * @param array|null $data
     * @return array
     */
    public function store($data = null)
    {
        if(!empty($data)) {
            $this->data = $data;
        }

        $errorMsg = $this->validator($this->data);
        if($errorMsg) {
            return error($errorMsg);
        }

        // 调用保存前回调函数
        if(!empty($this->savingCallback)) {
            $result = call_user_func($this->savingCallback,$this);
            if($result) {
                return $result;
            }
        }

        $data = $this->parseSubmitData($this->data);

        $result = $this->model->create($data);

        // 调用保存后回调函数
        if(!empty($this->savedCallback)) {
            $this->model = $result;
            return call_user_func($this->savedCallback,$this);
        }

        return $result;
    }

    /**
     * 表单内置的编辑方法
     *
     * @param string|null $id
     * @return $this
     */
    public function edit($id = null)
    {
        if(empty($id)) {
            $id = request('id');
        }

        $this->values = $this->model->findOrFail($id)->toArray();

        return $this;
    }

    /**
     * 表单内置的更新方法
     *
     * @param array|null $data
     * @return array
     */
    public function update($data = null)
    {
        if(!empty($data)) {
            $this->data = $data;
        }

        $errorMsg = $this->validator($this->data);
        if($errorMsg) {
            return error($errorMsg);
        }

        // 调用保存前回调函数
        if(!empty($this->savingCallback)) {
            $result = call_user_func($this->savingCallback,$this);
            if($result) {
                return $result;
            }
        }

        $data = $this->parseSubmitData($this->data);

        $result = $this->model->where('id',$data['id'])->update($data);

        // 调用保存后回调函数
        if(!empty($this->savedCallback)) {
            $this->model = $result;
            return call_user_func($this->savedCallback,$this);
        }

        return $result;
    }

    /**
     * 表单内置的删除方法
     *
     * @param string|null $id
     * @return bool
     */
    public function destroy($id = null)
    {
        if(empty($id)) {
            $id = request('id');
        }

        if(empty($id)) {
            return $this->error('参数错误！');
        }

        $result = $this->model->destroy($id);
        return $result;
    }

    /**
     * 创建页面显示前回调
     *
     * @param Closure $callback
     * @return $this
     */
    public function creating(Closure $callback = null)
    {
        $this->creatingCallback = $callback;

        return $this;
    }

    /**
     * 编辑页面显示前回调
     *
     * @param Closure $callback
     * @return $this
     */
    public function editing(Closure $callback = null)
    {
        $this->editingCallback = $callback;

        return $this;
    }

    /**
     * 保存数据前回调
     *
     * @param Closure $callback
     * @return $this
     */
    public function saving(Closure $callback = null)
    {
        $this->savingCallback = $callback;

        return $this;
    }

    /**
     * 保存数据后回调
     *
     * @param Closure $callback
     * @return $this
     */
    public function saved(Closure $callback = null)
    {
        $this->savedCallback = $callback;

        return $this;
    }

    /**
     * 是否禁用重置按钮
     *
     * @param bool $disabledResetButton
     * @return $this
     */
    public function disabledResetButton($disabledResetButton = true)
    {
        $this->disabledResetButton = $disabledResetButton;

        return $this;
    }

    /**
     * 重置按钮文字展示
     *
     * @param string $resetButtonText
     * @return $this
     */
    public function resetButtonText($resetButtonText)
    {
        $this->resetButtonText = $resetButtonText;

        return $this;
    }

    /**
     * 是否禁用提交按钮
     *
     * @param bool $disabledSubmitButton
     * @return $this
     */
    public function disabledSubmitButton($disabledSubmitButton = true)
    {
        $this->disabledSubmitButton = $disabledSubmitButton;

        return $this;
    }

    /**
     * 提交按钮文字展示
     *
     * @param string $submitButtonText
     * @return $this
     */
    public function submitButtonText($submitButtonText)
    {
        $this->submitButtonText = $submitButtonText;

        return $this;
    }

    /**
     * 是否禁用返回按钮
     *
     * @param bool $disabledBackButton
     * @return $this
     */
    public function disabledBackButton($disabledBackButton = true)
    {
        $this->disabledBackButton = $disabledBackButton;

        return $this;
    }

    /**
     * 返回按钮文字展示
     *
     * @param string $backButtonText
     * @return $this
     */
    public function backButtonText($backButtonText)
    {
        $this->backButtonText = $backButtonText;

        return $this;
    }

    /**
     * 获取行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        $class = Arr::get(static::$formFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public function __call($method, $parameters)
    {
        if ($className = static::getCalledClass($method)) {

            $column = Arr::get($parameters, 0, ''); //[0];
            $element = new $className($column, array_slice($parameters, 1));
            $this->items[] = $element;

            return $element;
        }
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        $this->key();

        // 调用创建页面展示前回调函数
        if(Str::endsWith(\request()->route()->getName(), ['/create'])) {
            if(!empty($this->creatingCallback)) {
                call_user_func($this->creatingCallback,$this);
            }
        }

        // 调用编辑页面展示前回调函数
        if(Str::endsWith(\request()->route()->getName(), ['/edit'])) {
            if(!empty($this->editingCallback)) {
                call_user_func($this->editingCallback,$this);
            }
        }

        // 为空，初始化表单数据
        $this->initialValues($this->values);

        return array_merge([
            'api' => $this->api,
            'colon' => $this->colon,
            'initialValues' => $this->initialValues,
            'labelAlign' => $this->labelAlign,
            'title' => $this->title,
            'width' => $this->width,
            'name' => $this->name,
            'preserve' => $this->preserve,
            'requiredMark' => $this->requiredMark,
            'scrollToFirstError' => $this->scrollToFirstError,
            'size' => $this->size,
            'dateFormatter' => $this->dateFormatter,
            'layout' => $this->layout,
            'labelCol' => $this->labelCol,
            'wrapperCol' => $this->wrapperCol,
            'buttonWrapperCol' => $this->buttonWrapperCol,
            'disabledResetButton' => $this->disabledResetButton,
            'resetButtonText' => $this->resetButtonText,
            'disabledSubmitButton' => $this->disabledSubmitButton,
            'submitButtonText' => $this->submitButtonText,
            'disabledBackButton' => $this->disabledBackButton,
            'backButtonText' => $this->backButtonText,
            'items' => $this->items,
        ], parent::jsonSerialize());
    }
}
