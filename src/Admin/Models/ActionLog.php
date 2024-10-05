<?php

namespace QuarkCloudIO\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ActionLog extends Model
{
    /**
     * 属性黑名单
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 为 array / JSON 序列化准备日期格式
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * 一对一，获取管理员信息
     *
     * @param  void
     * @return object
     */
    public function admin()
    {
        return $this->hasOne('QuarkCloudIO\QuarkAdmin\Models\Admin', 'id', 'object_id');
    }
}