<?php

namespace QuarkCloudIO\Quark\Component\Action;

use QuarkCloudIO\Quark\Component\Action\Modal;
use QuarkCloudIO\Quark\Component\Element;
use QuarkCloudIO\Quark\Component\Traits\Button;
use Closure;

class Action extends Element
{
    use Button;

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close。
     *
     * @var string
     */
    public $actionType;

    /**
     * 当action 的作用类型为submit的时候，可以指定提交哪个表格，submitForm为提交表单的key值，为空时提交当前表单
     *
     * @var string
     */
    public $submitForm = null;

    /**
     * 触发行为跳转链接
     *
     * @var string
     */
    public $href;

    /**
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @var string
     */
    public $target;

    /**
     * 执行的js语句
     *
     * @var string
     */
    public $js;

    /**
     * 触发行为打开弹窗
     *
     * @var array
     */
    public $modal = null;

    /**
     * 触发行为打开抽屉
     *
     * @var array
     */
    public $drawer = null;

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
     * 执行行为的接口链接
     *
     * @var string
     */
    public $api;

    /**
     * 执行成功后刷新的组件
     *
     * @var string
     */
    public $reload;

    /**
     * 是否具有loading
     *
     * @var bool
     */
    public $withLoading = false;

    /**
     * 初始化容器
     *
     * @param  string  $label
     * @return void
     */
    public function __construct($label = '')
    {
        $this->component = 'action';
        $this->label = $label;

        return $this;
    }

    /**
     * 【必填】这是 action 最核心的配置，来指定该 action 的作用类型，支持：ajax、link、url、drawer、dialog、confirm、cancel、prev、next、copy、close。
     *
     * @param  string  $actionType
     * @return $this
     */
    public function actionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * 当action 的作用类型为submit的时候，可以指定提交哪个表格，submitForm为提交表单的key值，为空时提交当前表单
     *
     * @param  string  $formKey
     * @return $this
     */
    public function submitForm($formKey)
    {
        $this->submitForm = $formKey;

        return $this;
    }

    /**
     * 点击跳转的地址，指定此属性 button 的行为和 a 链接一致
     *
     * @param  string  $href
     * @return $this
     */
    public function href($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @param  string  $target
     * @return $this
     */
    public function target($target)
    {
        if(!in_array($target,['_blank', '_self', '_parent', '_top'])) {
            throw new \Exception("Argument must be in '_blank', '_self', '_parent', '_top'!");
        }

        $this->target = $target;

        return $this;
    }

    /**
     * 设置跳转链接
     *
     * @param  string  $href
     * @param  string  $target _blank,_self,_parent
     * @return $this
     */
    public function link($href = null, $target = '_self')
    {
        $this->href($href);
        $this->target($target);
        $this->actionType = 'link';

        return $this;
    }

    /**
     * 执行的js语句
     *
     * @param  string  $js
     * @return $this
     */
    public function js($js)
    {
        $this->js = $js;

        return $this;
    }

    /**
     * 弹窗
     *
     * @param  Closure  $modal
     * @return $this
     */
    public function modal(Closure $callback = null)
    {
        $this->modal = $callback(new Modal);
        $this->actionType = 'modal';

        return $this;
    }

    /**
     * 抽屉
     *
     * @param  Closure  $drawer
     * @return $this
     */
    public function drawer(Closure $callback = null)
    {
        $this->drawer = $callback(new Drawer);
        $this->actionType = 'drawer';

        return $this;
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
     *  执行行为的接口链接
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        $this->actionType = 'ajax';

        return $this;
    }

    /**
     *  执行成功后刷新的组件
     *
     * @param  string  $reload
     * @return $this
     */
    public function reload($reload)
    {
        $this->reload = $reload;

        return $this;
    }

    /**
     *  是否具有loading
     *
     * @param  bool  $loading
     * @return $this
     */
    public function withLoading($loading = true)
    {
        $this->withLoading = $loading;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key($this->label.$this->api.$this->href.json_encode($this->modal).json_encode($this->drawer), true);
        }

        return array_merge([
            'label' => $this->label,
            'block' => $this->block,
            'danger' => $this->danger,
            'disabled' => $this->disabled,
            'ghost' => $this->ghost,
            'icon' => $this->icon,
            'shape' => $this->shape,
            'size' => $this->size,
            'actionType' => $this->actionType,
            'href' => $this->href,
            'target' => $this->target,
            'js' => $this->js,
            'type' => $this->type,
            'modal' => $this->modal,
            'drawer' => $this->drawer,
            'confirmTitle' => $this->confirmTitle,
            'confirmText' => $this->confirmText,
            'confirmType' => $this->confirmType,
            'api' => $this->api,
            'reload' => $this->reload,
            'submitForm' => $this->submitForm,
            'withLoading' => $this->withLoading
        ], parent::jsonSerialize());
    }
}
