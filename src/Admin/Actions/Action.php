<?php

namespace QuarkCloudIO\QuarkAdmin\Actions;

use Illuminate\Support\Str;

/**
 * Class Action.
 */
abstract class Action
{
    /**
     * 名称
     *
     * @var string
     */
    public $name = null;

    /**
     * 执行成功后刷新的组件
     *
     * @var string
     */
    public $reload = false;

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、modal、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'ajax';

    /**
     * 当action 的作用类型为submit的时候，可以指定提交哪个表格，submitForm为提交表单的key值，为空时提交当前表单
     *
     * @var string
     */
    public $submitForm = null;

    /**
     * 设置按钮的图标组件
     *
     * @var string|bool
     */
    public $icon = false;

    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'default';

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 是否具有loading，当action 的作用类型为ajax,submit时有效
     *
     * @var bool
     */
    public $withLoading = false;

    /**
     * 行为表单字段
     *
     * @var array|object
     */
    public $fields = [];

    /**
     * 确认信息的标题
     *
     * @var array
     */
    public $confirmTitle;

    /**
     * 确认信息的内容
     *
     * @var array
     */
    public $confirmText;

    /**
     * 确认信息弹框的类型
     *
     * @var array
     */
    public $confirmType;

    /**
     * 只在列表页展示
     *
     * @var bool
     */
    public $onlyOnIndex = false;

    /**
     * 只在表单页展示
     *
     * @var bool
     */
    public $onlyOnForm = false;

    /**
     * 只在详情页展示
     *
     * @var bool
     */
    public $onlyOnDetail = false;

    /**
     * 在列表页展示
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * 在列表页表格行内展示
     *
     * @var bool
     */
    public $showOnIndexTableRow = false;

    /**
     * 在列表页表格多选弹出层展示
     *
     * @var bool
     */
    public $showOnIndexTableAlert = false;

    /**
     * 在表单页展示
     *
     * @var bool
     */
    public $showOnForm = false;

    /**
     * 在表单页右上角自定义区域展示
     *
     * @var bool
     */
    public $showOnFormExtra = false;

    /**
     * 在详情页展示
     *
     * @var bool
     */
    public $showOnDetail = false;

    /**
     * 在详情页右上角自定义区域展示
     *
     * @var bool
     */
    public $showOnDetailExtra = false;

    /**
     * 行为key
     *
     * @return string
     */
    public function uriKey()
    {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * 获取名称
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 执行成功后刷新的组件
     *
     * @return string
     */
    public function reload()
    {
        return $this->reload;
    }

    /**
     * 行为接口接收的参数，当行为在表格行展示的时候，可以配置当前行的任意字段
     *
     * @return array
     */
    public function apiParams()
    {
        return [];
    }

    /**
     * 执行行为的接口
     *
     * @return string
     */
    public function api()
    {
        $params = $this->apiParams();
        $paramsUri = null;

        foreach ($params as $key => $value) {
            if(is_string($key)) {
                $paramsUri = $paramsUri.$key.'=${'.$value.'}&';
            } else {
                $paramsUri = $paramsUri.$value.'=${'.$value.'}&';
            }
        }

        $api = Str::beforeLast(Str::replaceFirst('api/','',\request()->path()), '/') . '/action/' . $this->uriKey();

        return $paramsUri ? $api . '?' . $paramsUri : $api;
    }

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close。
     *
     * @return string
     */
    public function actionType()
    {
        return $this->actionType;
    }

    /**
     * 当action 的作用类型为submit的时候，可以指定提交哪个表格，submitForm为提交表单的key值，为空时提交当前表单
     *
     * @return string
     */
    public function submitForm()
    {
        return $this->submitForm;
    }

    /**
     * 设置按钮类型，primary | ghost | dashed | link | text | default
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @return string
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * 是否具有loading，当action 的作用类型为ajax,submit时有效
     *
     * @return bool
     */
    public function withLoading()
    {
        return $this->withLoading;
    }

    /**
     * 设置按钮的图标组件
     *
     * @return string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * 行为表单字段
     *
     * @return mixed
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * 执行行为句柄
     *
     * @param  Fields  $fields
     * @param  Collection  $model
     * @return mixed
     */
    public function handle($fields, $model)
    {
        return [];
    }

    /**
     * 设置行为前的确认操作
     *
     * @param  string  $title
     * @param  string  $text
     * @param  string  $type
     * @return $this
     */
    public function withConfirm($title = null, $text = '', $type = 'modal')
    {
        if(!in_array($type,['modal', 'pop'])) {
            throw new \Exception("Argument must be in 'modal', 'pop'!");
        }

        $this->confirmTitle = $title;
        $this->confirmText = $text;
        $this->confirmType = $type;

        return $this;
    }

    /**
     * 只在列表页展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnIndex($value = true)
    {
        $this->onlyOnIndex = $value;
        $this->showOnIndex = $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnForm = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了列表页外展示
     *
     * @return $this
     */
    public function exceptOnIndex()
    {
        $this->showOnDetail = true;
        $this->showOnIndexTableRow = true;
        $this->showOnIndexTableAlert = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = true;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = true;
        $this->showOnIndex = false;

        return $this;
    }

    /**
     * 只在表单页展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnForm($value = true)
    {
        $this->showOnForm = $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了表单页外展示
     *
     * @return $this
     */
    public function exceptOnForm()
    {
        $this->showOnIndexTableAlert = true;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnIndexTableRow = true;
        $this->showOnForm = false;
        $this->showOnFormExtra = true;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 只在表单页右上角自定义区域展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnFormExtra($value = true)
    {
        $this->showOnForm = ! $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnFormExtra = $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了表单页右上角自定义区域外展示
     *
     * @return $this
     */
    public function exceptOnFormExtra()
    {
        $this->showOnIndexTableAlert = true;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnIndexTableRow = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = false;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 只在详情页展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnDetail($value = true)
    {
        $this->onlyOnDetail = $value;
        $this->showOnDetail = $value;
        $this->showOnIndex = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnForm = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了详情页外展示
     *
     * @return $this
     */
    public function exceptOnDetail()
    {
        $this->showOnIndex = true;
        $this->showOnDetail = false;
        $this->showOnIndexTableRow = true;
        $this->showOnIndexTableAlert = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = true;
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 只在详情页右上角自定义区域展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnDetailExtra($value = true)
    {
        $this->showOnForm = ! $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = $value;

        return $this;
    }

    /**
     * 除了详情页右上角自定义区域外展示
     *
     * @return $this
     */
    public function exceptOnDetailExtra()
    {
        $this->showOnIndexTableAlert = true;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnIndexTableRow = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = true;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = false;

        return $this;
    }

    /**
     * 在表格行内展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnIndexTableRow($value = true)
    {
        $this->showOnIndexTableRow = $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableAlert = ! $value;
        $this->showOnForm = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了表格行内外展示
     *
     * @return $this
     */
    public function exceptOnIndexTableRow()
    {
        $this->showOnIndexTableRow = false;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnIndexTableAlert = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = true;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 在表格多选弹出层展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnIndexTableAlert($value = true)
    {
        $this->showOnIndexTableAlert = $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnIndexTableRow = ! $value;
        $this->showOnForm = ! $value;
        $this->showOnFormExtra = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnDetailExtra = ! $value;

        return $this;
    }

    /**
     * 除了表格多选弹出层外展示
     *
     * @return $this
     */
    public function exceptOnIndexTableAlert()
    {
        $this->showOnIndexTableAlert = false;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnIndexTableRow = true;
        $this->showOnForm = true;
        $this->showOnFormExtra = true;
        $this->showOnDetail = true;
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 在列表页展示
     *
     * @return $this
     */
    public function showOnIndex()
    {
        $this->showOnIndex = true;

        return $this;
    }

    /**
     * 在表单页展示
     *
     * @return $this
     */
    public function showOnForm()
    {
        $this->showOnForm = true;

        return $this;
    }

    /**
     * 在表单页右上角自定义区域展示
     *
     * @return $this
     */
    public function showOnFormExtra()
    {
        $this->showOnFormExtra = true;

        return $this;
    }

    /**
     * 在详情页展示
     *
     * @return $this
     */
    public function showOnDetail()
    {
        $this->showOnDetail = true;

        return $this;
    }

    /**
     * 在详情页右上角自定义区域展示
     *
     * @return $this
     */
    public function showOnDetailExtra()
    {
        $this->showOnDetailExtra = true;

        return $this;
    }

    /**
     * 在表格行内展示
     *
     * @return $this
     */
    public function showOnIndexTableRow()
    {
        $this->showOnIndexTableRow = true;

        return $this;
    }

    /**
     * 在多选弹出层展示
     *
     * @return $this
     */
    public function showOnIndexTableAlert()
    {
        $this->showOnIndexTableAlert = true;

        return $this;
    }

    /**
     * 判断是否在列表页展示
     *
     * @return bool
     */
    public function shownOnIndex()
    {
        if ($this->onlyOnIndex == true) {
            return true;
        }

        if ($this->onlyOnDetail) {
            return false;
        }

        if ($this->onlyOnForm) {
            return false;
        }

        return $this->showOnIndex;
    }

    /**
     * 判断是否在表单页展示
     *
     * @return bool
     */
    public function shownOnForm()
    {
        if ($this->onlyOnForm == true) {
            return true;
        }

        if ($this->onlyOnDetail) {
            return false;
        }

        if ($this->onlyOnIndex) {
            return false;
        }

        return $this->showOnForm;
    }

    /**
     * 判断是否在详情页展示
     *
     * @return bool
     */
    public function shownOnDetail()
    {
        if ($this->onlyOnDetail) {
            return true;
        }

        if ($this->onlyOnIndex) {
            return false;
        }

        if ($this->onlyOnForm) {
            return false;
        }

        return $this->showOnDetail;
    }

    /**
     * 判断是否在表格行内展示
     *
     * @return bool
     */
    public function shownOnIndexTableRow()
    {
        return $this->showOnIndexTableRow;
    }

    /**
     * 判断是否在多选弹出层展示
     *
     * @return bool
     */
    public function shownOnIndexTableAlert()
    {
        return $this->showOnIndexTableAlert;
    }

    /**
     * 判断是否在表单页右上角自定义区域展示
     *
     * @return bool
     */
    public function shownOnFormExtra()
    {
        return $this->showOnFormExtra;
    }

    /**
     * 判断是否在详情页右上角自定义区域展示
     *
     * @return bool
     */
    public function shownOnDetailExtra()
    {
        return $this->showOnDetailExtra;
    }
}
