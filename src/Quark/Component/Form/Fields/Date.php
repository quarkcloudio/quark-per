<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Date extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'dateField';

    /**
     * 设置选择器类型,date | week | month | quarter | year
     *
     * @var string
     */
    public $picker = 'date';

    /**
     * 使用 format 属性，可以自定义日期显示格式
     *
     * @var string
     */
    public $format = 'YYYY-MM-DD';

    /**
     * 设置选择器类型,date | week | month | quarter | year
     *
     * @param  string $picker
     * @return $this
     */
    public function picker($picker)
    {
        if(!in_array($picker,['date', 'week', 'month', 'quarter', 'year'])) {
            throw new Exception("argument must be in 'date', 'week', 'month', 'quarter', 'year'!");
        }

        switch ($picker) {
            case 'date':
                $this->format('YYYY-MM-DD');
                break;

            case 'week':
                $this->format('MM-DD');
                break;

            case 'month':
                $this->format('YYYY-MM');
                break;

            case 'quarter':
                $this->format('YYYY-MM');
                break;

            case 'year':
                $this->format('YYYY');
                break;

            default:
                $this->format('YYYY-MM-DD');
                break;
        }

        $this->picker = $picker;
        return $this;
    }

    /**
     * 使用 format 属性，可以自定义日期显示格式
     *
     * @param  string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'picker' => $this->picker,
            'format' => $this->format
        ], parent::jsonSerialize());
    }
}
