<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Session;
class Base extends Controller
{
    public function _initialize()
    {
        $uid=Session::get('uid');
        if(!$uid){
        	exit('<script>alert("请先登录");location.href = "/home/Login/login"</script>');
        }
    }

}
