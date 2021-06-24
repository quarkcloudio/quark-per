<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ActionLog extends Model
{
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_id','url',
        'remark',
        'ip',
        'type',
        'status'
    ];

    /**
     * 一对一，获取管理员信息
     *
     * @param  void
     * @return object
     */
    public function admin()
    {
        return $this->hasOne('QuarkCMS\QuarkAdmin\Models\Admin', 'id', 'object_id');
    }
}