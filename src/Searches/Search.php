<?php

namespace QuarkCMS\QuarkAdmin\Searches;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class Search.
 */
abstract class Search
{
    /**
     * 字段
     *
     * @var string
     */
    public $column;

    /**
     * 名称
     *
     * @var string
     */
    public $name = null;

    /**
     * 控件类型
     *
     * @var string
     */
    public $type = 'input';

    /**
     * 操作符
     *
     * @var string
     */
    public $operator = null;

    /**
     * 初始化
     *
     * @param  void
     * @return void
     */
    public function __construct()
    {
        $this->column = lcfirst(class_basename(get_called_class()));
    }

    /**
     * 获取名称
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * 默认值
     *
     * @var string
     */
    public function default()
    {
        return true;
    }

    /**
     * 执行查询
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  mixed  $value
     * @return Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query;
    }

    /**
     * 属性
     *
     * @param  Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [];
    }

    /**
     * 魔术方法
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return array
     */
    public function __invoke($request, $query)
    {
        $search = $request->input('search');

        if(isset($search[$this->column])) {
            return $this->apply($request, $query, $search[$this->column]);
        }

        return $query;
    }
}
