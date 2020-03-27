<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
     public $timestamps = true;

     protected $fillable=['object_id','url','remark','ip','type','status'];   //允许批量赋值的字段

     public function admin()
     {
         return $this->hasOne('QuarkCMS\QuarkAdmin\Models\Admin', 'id', 'object_id');
     }
}