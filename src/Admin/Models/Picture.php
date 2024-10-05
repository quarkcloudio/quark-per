<?php

namespace QuarkCloudIO\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Picture extends Model
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
}