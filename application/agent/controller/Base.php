<?php

namespace app\agent\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
        $res=$this->getInfo();
        if(!$res){
            $this->redirect('index/index');
        }
    }

    private function getInfo()
    {
        $res=model('User')->getDecrypt(session('info','','agent'));
        if($res){
            $arr=explode('-',$res);
            $result=model('User')->where(['mobile'=>$arr[0],'id'=>$arr[1]])->find();
            if($result){
                return $arr[1];
            }else{
                return false;
            }
        }else{
            $this->redirect('index/index');
        }
    }

    public function getPage($object)//分页
    {
        if($object){
            $pages['currentPage']= $object->currentPage();
            $pages['total'] = $object->total();
            $pages['page'] = $object->render();
        }else{
            $pages['currentPage']=$pages['total']=$pages['page']=0;
        }
        return $pages;
    }
}
