<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class File extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'fileField';

    /**
     * 上传按钮的标题
     *
     * @var string
     */
    public $button = '上传文件';

    /**
     * 上传文件大小，默认2M
     *
     * @var number
     */
    public $limitSize = 2;

    /**
     * 上传文件的类型
     *
     * @var array
     */
    public $limitType = ['jpeg','png','doc','docx'];

    /**
     * 多文件上传的数量限制
     *
     * @var number
     */
    public $limitNum = 3;

    /**
     * 文件上传api接口
     *
     * @var string
     */
    public $api = '/api/admin/file/upload';

    /**
     * 上传文件大小限制
     *
     * @param  number  $limitSize
     * @return $this
     */
    public function limitSize($limitSize)
    {
        $this->limitSize = $limitSize;
        return $this;
    }

    /**
     * 上传文件类型限制
     *
     * @param  string  $limitType
     * @return $this
     */
    public function limitType($limitType)
    {
        $this->limitType = $limitType;
        return $this;
    }

    /**
     * 上传文件数量限制
     *
     * @param  number  $limitNum
     * @return $this
     */
    public function limitNum($limitNum)
    {
        $this->limitNum = $limitNum;
        return $this;
    }

    /**
     * 上传的api接口
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
     * 上传按钮的标题
     *
     * @param  string  $text
     * @return $this
     */
    public function button($text)
    {
        $this->button = $text;
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
            'api' => $this->api,
            'button' => $this->button,
            'limitSize' => $this->limitSize,
            'limitType' => $this->limitType,
            'limitNum' => $this->limitNum
        ], parent::jsonSerialize());
    }
}
