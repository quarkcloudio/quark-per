<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use QuarkCMS\QuarkAdmin\Components\Table\Model;

class Form extends Element
{
    /**
     * 表格标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 表单默认值，只有初始化以及重置时生效
     *
     * @var array
     */
    public $initialValues = null;

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
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct(Eloquent $model)
    {
        $this->component = 'form';
        $this->model = new Model($model,$this);
        return $this;
    }

    /**
     * 表单标题
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
     * 读取模型
     *
     * @return $this
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        $this->key(__CLASS__.$this->title);

        return array_merge([
            'title' => $this->title
        ], parent::jsonSerialize());
    }
}
