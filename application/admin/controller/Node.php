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
}
