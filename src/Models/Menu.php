<?php

namespace QuarkCMS\QuarkAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Menu extends Model
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
     * 获取菜单的有序列表
     *
     * @param  void
     * @return object
     */
    public static function orderedList()
    {
        $menus = static::query()->where('guard_name','admin')
        ->orderBy('sort', 'asc')
        ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $menuTrees = list_to_tree($menus,'id','pid','children',0);
        $menuTreeLists = tree_to_ordered_list($menuTrees,0,'name','children');

        $list[0] = '根节点';
        foreach ($menuTreeLists as $key => $menuTreeList) {
            $list[$menuTreeList['id']] = $menuTreeList['name'];
        }

        return $list;
    }
}