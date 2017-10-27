<?php

namespace app\agent\controller;

use think\Controller;
use think\Request;

class Main extends Base
{
    public function index()
    {
        $user_id=input('param.id');
        $level=model('User')->get($user_id)->level;
        return view('main/index',['level'=>$level]);
    }

    public function base(Request $request)
    {
        $ip=$request->ip();
        $time=getTime();
        $this->assign('name',session('name','','agent'));
        $this->assign('times',$time);
        $this->assign('ip',$ip);
        $this->assign('time',time());
        return view('main/base');
    }
}
