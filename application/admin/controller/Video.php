<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Video extends Base
{
    public function index()//加载添加视频视图
    {
        return view('video/index');
    }

    public function videoList()//视频列表
    {

    }
}
