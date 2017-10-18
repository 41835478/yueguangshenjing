<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Main extends Base
{
    public function index()
    {
        return view('main/index');
    }

    public function base()
    {
        $res=action('member/getStatic');
        $result=model('Admin')->get($res[1])->toArray();
        $time=getTime();
        $this->assign('time',$time);
        $this->assign('res',$result);
        return view('main/base');
    }
}
