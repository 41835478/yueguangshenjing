<?php

namespace app\admin\controller;

use app\admin\model\Config;
use think\Request;

class Statistics extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        //
        return $this->fetch("statistics/index");
    }

}
