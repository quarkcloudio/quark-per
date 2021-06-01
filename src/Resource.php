<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource
{
    use Layout;

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $pagination = false;

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 配置分页
     *
     * @return mixed
     */
    public static function pagination()
    {
        return static::$pagination;
    }

    /**
     * 将字段转换表格列
     *
     * @param  Request $request
     * @return array
     */
    protected function fieldsToColumns($request)
    {
        $fields = $this->fields($request);
        $columns = [];

        foreach ($fields as $key => $value) {
            if($value->showOnIndex) {
                $columns[] = Column::make($value->name, $value->label);
            }
        }

        return $columns;
    }

    /**
     * 获取表格数据
     *
     * @param  Request $request
     * @return array
     */
    protected function getData($request)
    {
        $model = static::newModel();

        if(static::pagination()) {
            return $model->paginate(request('pageSize',static::pagination()));
        } else {
            return $model->get()->toArray();
        }
    }

    /**
     * 列表资源
     *
     * @param  void
     * @return array
     */
    public function indexResource(Request $request)
    {
        $data = $this->getData($request);

        $table = Table::key('table')
        ->title($this->title)
        ->toolBar(false)
        ->columns($this->fieldsToColumns($request))
        ->batchActions([]);
        
        $table->search(function($search) {

            $search->where('title', '搜索内容',function ($query) {
                $query->where('title', 'like', "%{input}%");
            })->placeholder('搜索内容');
        
            $search->equal('status', '所选状态')
            ->select([''=>'全部',1=>'正常',2=>'已禁用'])
            ->placeholder('选择状态')
            ->width(110);
        
            $search->between('created_at', '发布时间')
            ->datetime();
        });

        if(static::pagination()) {
            $table = $table->datasource($data->items())->pagination($data->currentPage(), $data->perPage(), $data->total());
        } else {
            $table = $table->datasource($data);
        }

        return $this->setLayoutContent($table);
    }
}
