<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable=['name','guard_name','icon','type','pid','sort','path','show','status'];   //允许批量赋值的字段

    /**
     * 自动查询
     *
     * @param  void
     * @return object
     */
    public static function withQuerys()
    {
        $self = new static;
        $requestData = request()->all();

        if(!empty($requestData)) {
            foreach ($requestData as $key => $value) {
            
                if(in_array($key, ['name', 'guard_name'])) {
                    $self = $self->where($key, 'like', "%$value%");
                }
    
                if(in_array($key, ['show', 'status'])) {
                    $self = $self->where($key, $value);
                }
            }
        }

        return $self;
    }

}