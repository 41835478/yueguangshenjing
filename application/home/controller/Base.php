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
        $user=db('user')->where('id',$uid)->find();
        if($user['status']==2){
        	exit('<script>alert("该账户已被锁定,暂无法访问!");location.href = "/home/Login/login"</script>');
        }

    }

}
