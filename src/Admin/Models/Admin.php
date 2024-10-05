<?php

namespace QuarkCloudIO\QuarkAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use DateTimeInterface;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    /**
     * 属性黑名单
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
     * 一对一，获取头像
     *
     * @param  void
     * @return object
     */
    public function picture()
    {
        return $this->hasOne('QuarkCloudIO\QuarkAdmin\Models\Picture', 'id', 'avatar');
    }

    /**
     * 更新最后登录信息
     *
     * @param  void
     * @return object
     */
    public function updateLastLoginInfo()
    {
        $this->update(
            [
                'last_login_ip' => request()->ip(),
                'last_login_time' => date('Y-m-d H:i:s')
            ]
        );
    }
}