<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Quark;
use QuarkCMS\QuarkAdmin\Models\Config;

class QuarkController extends Controller
{
    /**
     * quark object
     *
     * @var object
     */
    protected $quark;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct() {
        $this->quark = new Quark;
    }

    /**
     * Get quark info.
     *
     * @param Request $request
     *
     * @return array
     */
    public function info(Request $request)
    {
        $info = $this->quark->info();

        if($info) {
            return success('ok','',$info);
        } else {
            return error('failed!');
        }
    }

    /**
     * 获取layout布局
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function layout(Request $request)
    {
        $layout = $this->quark->layout();

        if($layout) {
            return success('ok','',$layout);
        } else {
            return error('failed!');
        }
    }

    /**
     * test
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        dump($this->quark->form(new Config));
    }
}
