<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Node extends Base
{
    public function index()//节点管理
    {
        return view('node/index');
    }

    public function nodeView()//加载添加节点视图
    {
        return view('node/addNode');
    }
}
