<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Icon extends Item
{
    public  $options;

    function __construct($name,$label = '') {
        $this->component = 'icon';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->options = [
            'lock','unlock','bars','book','calendar','cloud','cloud-download',
            'code','copy','credit-card','delete','desktop','download','ellipsis',
            'more','file','file-text','file-unknown','file-pdf','file-word',
            'file-excel','file-jpg','file-ppt','file-markdown','file-add',
            'folder','folder-open','folder-add','hdd','frown','meh','smile',
            'inbox','laptop','appstore','link','mail','mobile','notification',
            'paper-clip','picture','poweroff','reload','search','setting',
            'share-alt','shopping-cart','tablet','tag','tags','to-top','upload',
            'user','video-camera','home','loading','loading-3-quarters',
            'cloud-upload','star','heart','environment','eye','eye-invisible',
            'camera','save','team','solution','phone','filter','exception',
            'import','export','customer-service','qrcode','scan','like','dislike',
            'message','pay-circle','calculator','pushpin','bulb','select','switcher',
            'rocket','bell','disconnect','database','compass','barcode','hourglass',
            'key','flag','layout','printer','sound','usb','skin','tool','sync','wifi',
            'car','schedule','user-add','user-delete','usergroup-add','usergroup-delete',
            'man','woman','shop','gift','idcard','medicine-box','red-envelope','coffee',
            'copyright','trademark','safety','wallet','bank','trophy','contacts','global',
            'shake','api','fork','dashboard','table','profile','alert','audit','branches',
            'build','border','crown','experiment','fire','money-collect','property-safety',
            'read','reconciliation','rest','security-scan','insurance','interation','safety-certificate',
            'project','thunderbolt','block','cluster','deployment-unit','dollar','euro','pound',
            'file-done','file-exclamation','file-protect','file-search','file-sync','gateway',
            'gold','robot','shopping'
        ];
    }

    /**
     * 创建组件
     *
     * @param  string $name
     * @param  string $label
     * @return object
     */
    static function make($name,$label = '')
    {
        $self = new self();

        $self->name = $name;
        if(empty($label)) {
            $self->label = $name;
        } else {
            $self->label = $label;
        }

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * 输入框宽度
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
}
