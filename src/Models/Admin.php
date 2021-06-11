<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

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
     * 日期格式
     *
     * @var array
     */
    protected $dates = ['delete_at'];

    /**
     * 一对一，获取头像
     *
     * @param  void
     * @return object
     */
    public function picture()
    {
        return $this->hasOne('QuarkCMS\QuarkAdmin\Models\Picture', 'id', 'avatar');
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