<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Code extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return $this->fetch("code/qrcode");
    }

}
