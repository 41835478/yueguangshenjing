<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
//        $request= Request::instance();
//        var_dump($request->controller());
//        var_dump($request->action());
//        var_dump($request->module());
        $res=$this->getInfo();
        if(!$res){
            $this->redirect('index/index');
        }
    }

    private function getInfo()
    {
        $res=model('Admin')->getDecrypt(session('info'));
        if($res){
            $arr=explode('-',$res);
            $result=model('Admin')->where(['mobile'=>$arr[0],'id'=>$arr[1],'status'=>1])->find();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            $this->redirect('index/index');
        }
    }
}
