<?php

namespace QuarkCMS\QuarkAdmin\Actions;

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
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType = 'ajax';

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
    public $showStyle = 'default';

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'default';

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
    public $showOnIndex = true;

    /**
     * 在详情页展示
     *
     * @var bool
     */
    public $showOnDetail = true;

    /**
     * 在表格行内展示
     *
     * @var bool
     */
    public $showOnTableRow = false;

    /**
     * 在表格多选弹出层展示
     *
     * @var bool
     */
    public $showOnTableAlert = false;

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
     * 行为接口接收的参数，当行为在表格行展示的时候，可以配置当前行的任意字段
     *
     * @return array
     */
    public function apiParams()
    {
        return ['id'];
    }

    /**
     * 执行行为的接口
     *
     * @return string
     */
    public function api()
    {
        $params = $this->apiParams();
        $paramsUri = '';

        foreach ($params as $key => $value) {
            if(is_string($key)) {
                $paramsUri = $paramsUri.$key.'={'.$value.'}&';
            } else {
                $paramsUri = $paramsUri.$value.'={'.$value.'}&';
            }
        }

        return Str::beforeLast(Str::replaceFirst('api/','',\request()->path()), '/') . '/action/' . $this->uriKey() . '?' . $paramsUri;
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
     * 设置按钮类型，primary | ghost | dashed | link | text | default
     *
     * @return string
     */
    public function showStyle()
    {
        return $this->showStyle;
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
     * @param  Collection  $models
     * @return mixed
     */
    public function handle($fields, $models)
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
        $this->showOnTableRow = ! $value;
        $this->showOnTableAlert = ! $value;

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
        $this->showOnTableRow = true;
        $this->showOnTableAlert = true;
        $this->showOnIndex = false;

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
        $this->showOnTableRow = ! $value;
        $this->showOnTableAlert = ! $value;

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
        $this->showOnTableRow = true;
        $this->showOnTableAlert = true;

        return $this;
    }

    /**
     * 在表格行内展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnTableRow($value = true)
    {
        $this->showOnTableRow = $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnTableAlert = ! $value;

        return $this;
    }

    /**
     * 除了表格行内外展示
     *
     * @return $this
     */
    public function exceptOnTableRow()
    {
        $this->showOnTableRow = false;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnTableAlert = true;

        return $this;
    }

    /**
     * 在表格多选弹出层展示
     *
     * @param  bool  $value
     * @return $this
     */
    public function onlyOnTableAlert($value = true)
    {
        $this->showOnTableAlert = $value;
        $this->showOnIndex = ! $value;
        $this->showOnDetail = ! $value;
        $this->showOnTableRow = ! $value;

        return $this;
    }

    /**
     * 除了表格多选弹出层外展示
     *
     * @return $this
     */
    public function exceptOnTableAlert()
    {
        $this->showOnTableAlert = false;
        $this->showOnIndex = true;
        $this->showOnDetail = true;
        $this->showOnTableRow = true;

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
     * 在表格行内展示
     *
     * @return $this
     */
    public function showOnTableRow()
    {
        $this->showOnTableRow = true;

        return $this;
    }

    /**
     * 在多选弹出层展示
     *
     * @return $this
     */
    public function showOnTableAlert()
    {
        $this->showOnTableAlert = true;

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

        return $this->showOnIndex;
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

        return $this->showOnDetail;
    }

    /**
     * 判断是否在表格行内展示
     *
     * @return bool
     */
    public function shownOnTableRow()
    {
        return $this->showOnTableRow;
    }

    /**
     * 判断是否在多选弹出层展示
     *
     * @return bool
     */
    public function shownOnTableAlert()
    {
        return $this->showOnTableAlert;
    }
}
