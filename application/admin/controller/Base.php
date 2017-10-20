<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
        $res=$this->getInfo();
        if(!$res){
            $this->redirect('index/index');
        }else{
            $result=$this->judgeRule($res);
            if(!$result){
                $this->redirect('index/rulePage');
            }
        }
    }

    private function getInfo()
    {
        $res=model('Admin')->getDecrypt(session('info','','admin'));
        if($res){
            $arr=explode('-',$res);
            $result=model('Admin')->where(['mobile'=>$arr[0],'id'=>$arr[1],'status'=>1])->find();
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

    public function judgeRule($id)//判断管理员是否有操作权限
    {
        $request= Request::instance();
        $controller=$request->controller();
        $action=$request->action();
        $user=model('Admin')->get($id);
        if($user->role_id){
            $role=model('Roles')->get($user->role_id);
            if($role->rule){
                $str=strtolower($controller).'-'.strtolower($action);
                return $this->mergeStr($role->rule,$str);
            }else{//若没有分配权限说明拥有所有权限
                return true;
            }
        }
        return false;
    }

    public function mergeStr($node_id,$str)
    {
        $arr=explode(',',$node_id);
        $nodes=model('Nodes')->field(['controller_name','action_name'])->whereIn('id',$arr)->select();
        $arr=array();
        foreach($nodes as $k=>$v){
            $arr[]=$v['controller_name'].'-'.$v['action_name'];
        }
        if(in_array($str,$arr)){
            return true;
        }
        return false;
    }
}
