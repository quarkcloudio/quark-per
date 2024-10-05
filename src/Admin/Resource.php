<?php

namespace QuarkCloudIO\QuarkAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use QuarkCloudIO\Quark\Facades\ToolBar;
use QuarkCloudIO\Quark\Facades\Action;

/**
 * Class Resource.
 */
abstract class Resource extends JsonResource
{
    use Layout;
    use PerformsQueries;
    use PerformsValidation;
    use ResolvesFields;
    use ResolvesActions;
    use ResolvesFilters;
    use ResolvesSearches;

    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = null;

    /**
     * 页面子标题
     *
     * @var string
     */
    public static $subTitle = null;

    /**
     * 模型
     *
     * @var string
     */
    public static $model = null;

    /**
     * The underlying model resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    public $resource;

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = false;

    /**
     * 是否有导入功能
     *
     * @var bool
     */
    public static $withImport = false;

    /**
     * 是否有导出功能
     *
     * @var bool
     */
    public static $withExport = false;

    /**
     * 列表页轮询
     *
     * @var null|number
     */
    public static $indexPolling = null;

    /**
     * 详情页列数
     *
     * @var int
     */
    public static $detailColumn = 2;

    /**
     * 初始化
     *
     * @var mixed
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * 页面标题
     *
     * @return string
     */
    public function title()
    {
        return static::$title;
    }

    /**
     * 页面子标题
     *
     * @return string
     */
    public function subTitle()
    {
        return static::$subTitle;
    }

    /**
     * 刷新的模型
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * 模型
     *
     * @return mixed
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * 配置分页
     *
     * @return mixed
     */
    public static function pagination()
    {
        return static::$perPage;
    }

    /**
     * 列表页表格主体
     *
     * @param  Request $request
     * @return array
     */
    public function indexExtraRender($request)
    {
        return null;
    }

    /**
     * 列表页工具栏
     *
     * @param  Request $request
     * @return array
     */
    public function indexToolBar($request)
    {
        return ToolBar::make($this->indexTitle($request))->actions($this->indexActions($request));
    }

    /**
     * 判断当前页面是否为列表页面
     *
     * @return bool
     */
    public function isIndex()
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        return in_array(end($uri), ['index']);
    }

    /**
     * 判断当前页面是否为创建页面
     *
     * @return bool
     */
    public function isCreating()
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        return in_array(end($uri), ['create', 'store']);
    }

    /**
     * 判断当前页面是否为编辑页面
     *
     * @return bool
     */
    public function isEditing()
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        return in_array(end($uri), ['edit', 'save']);
    }

    /**
     * 判断当前页面是否为详情页面
     *
     * @return bool
     */
    public function isDetail()
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        return in_array(end($uri), ['detail']);
    }

    /**
     * 判断当前页面是否为导出页面
     *
     * @return bool
     */
    public function isExport()
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        return in_array(end($uri), ['export']);
    }

    /**
     * 列表标题
     *
     * @param  Request $request
     * @return array
     */
    public function indexTitle(Request $request)
    {
        return $this->title() . '列表';
    }

    /**
     * 表单接口
     *
     * @param  Request $request
     * @return string
     */
    public function formApi($request)
    {
        return null;
    }

    /**
     * 创建表单的接口
     *
     * @param  Request $request
     * @return string
     */
    public function creationApi($request)
    {
        if($this->formApi($request)) {
            return $this->formApi($request);
        }

        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if(in_array(end($uri), ['index'])) {
            return Str::replaceLast('/index', '/store', 
                Str::replaceFirst('api/','',\request()->path())
            );
        }

        return Str::replaceLast('/create', '/store', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }

    /**
     * 更新表单的接口
     *
     * @param  Request $request
     * @return string
     */
    public function updateApi($request)
    {
        if($this->formApi($request)) {
            return $this->formApi($request);
        }

        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if(in_array(end($uri), ['index'])) {
            return Str::replaceLast('/index', '/save', 
                Str::replaceFirst('api/','',\request()->path())
            );
        }

        return Str::replaceLast('/edit', '/save', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }

    /**
     * 编辑页面获取表单数据接口
     *
     * @param  Request $request
     * @return string
     */
    public function editValueApi($request)
    {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if(in_array(end($uri), ['index'])) {
            return Str::replaceLast('/index', '/edit/values?id=${id}', 
                Str::replaceFirst('api/','',\request()->path())
            );
        }

        return Str::replaceLast('/edit', '/edit/values?id=${id}', 
            Str::replaceFirst('api/','',\request()->path())
        );
    }

    /**
     * 表单标题
     *
     * @param  Request $request
     * @return array
     */
    public function formTitle(Request $request)
    {
        if($this->isCreating()) {
            return '创建' . $this->title();
        } elseif ($this->isEditing()) {
            return '编辑' . $this->title();
        }

        return $this->title();
    }

    /**
     * 详情页标题
     *
     * @param  Request $request
     * @return array
     */
    public function detailTitle(Request $request)
    {
        return $this->title() . '详情';
    }

    /**
     * 列表页面显示前回调
     * 
     * @param Request $request
     * @param mixed $list
     * @return mixed
     */
    public function beforeIndexShowing(Request $request, $list)
    {
        return $list;
    }

    /**
     * 详情页页面显示前回调
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function beforeDetailShowing(Request $request, $data)
    {
        return $data;
    }

    /**
     * 创建页面显示前回调
     * 
     * @param Request $request
     * @return array
     */
    public function beforeCreating(Request $request)
    {
        return [];
    }

    /**
     * 编辑页面显示前回调
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function beforeEditing(Request $request, $data)
    {
        return $data;
    }

    /**
     * 保存数据前回调
     *
     * @param Request $request
     * @param array $submitData
     * @return array
     */
    public function beforeSaving(Request $request, $submitData)
    {
        return $submitData;
    }

    /**
     * 保存数据后回调
     *
     * @param Request $request
     * @param mixed $model
     * @return array|object
     */
    public function afterSaved(Request $request, $model)
    {
        return $model;
    }

    /**
     * 数据导出前回调
     * 
     * @param Request $request
     * @param mixed $list
     * @return mixed
     */
    public function beforeExporting(Request $request, $list)
    {
        return $list;
    }

    /**
     * 数据导入模板显示前回调
     * 
     * @param Request $request
     * @param mixed $titles
     * @return mixed
     */
    public function beforeImportTemplateShowing(Request $request, $titles)
    {
        return $titles;
    }

    /**
     * 数据导入前回调
     * 
     * @param Request $request
     * @param mixed $list
     * @return mixed
     */
    public function beforeImporting(Request $request, $list)
    {
        return $list;
    }

    /**
     * 将资源转换成数组
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->getFields($request) as $value) {
            if(!empty($value->name)) {
                $this->resource->{$value->name} = ($value->callback && ($this->isIndex() || $this->isDetail() || $this->isExport())) ? call_user_func($value->callback) : $this->{ $value->name };
            
                // 关联属性
                if (Str::contains($value->name, '.')) {
                    list($relation, $relationColumn) = explode('.', $value->name);
    
                    if(isset($this->resource->{$relation})) {
                        $this->resource->{$value->name} = $this->resource->{$relation}->$relationColumn;
                    }
                }
            }
        }

        return $this->resource->toArray();
    }
}
