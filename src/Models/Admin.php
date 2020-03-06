<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'nickname',
        'email',
        'phone',
        'sex',
        'password',
        'avatar',
        'wechat_openid',
        'wechat_unionid',
        'qq_openid',
        'weibo_uid',
        'last_login_ip',
        'last_login_time',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['delete_at'];

    public function picture()
    {
        return $this->hasOne('QuarkCMS\QuarkAdmin\Models\Picture', 'id', 'avatar');
    }
}