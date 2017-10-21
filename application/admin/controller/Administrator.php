<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Administrator extends Base
{
    public function index()//管理员管理
    {
        $data=model('Admin')->field(['id','name','mobile','pic','role_id'])->select();
        foreach ($data as $k=>$v){
            $data[$k]['role_name']=$this->getRoleName($v['role_id']);
        }
        $role=model('Roles')->field(['id','role_name'])->select();
        return view('administrator/index',['data'=>$data,'role'=>$role]);
    }

    public function addUser()//添加管理员
    {
        $date=input('post.');
        $validate=validate('Administrator');
        if(!$validate->check($date)){
            $url=popBox('error',$validate->getError());
        }else{
            $data['name']=$date['name'];
            $data['mobile']=$date['mobile'];
            $data['pwd']=$date['pwd'];
            $data['role_id']=$date['role'];
            $res=model('Admin')->create($data);
            if($res){
                $url=popBox('success','添加管理员成功');
            }else{
                $url=popBox('error','添加管理员失败');
            }
        }
        $this->redirect($url);
    }

    public function editRole()//修改角色
    {
        $date=input('post.');
        $user=model('Admin')->get($date['id']);
        $user->role_id=$date['role'];
        if($user->save()){
            $url=popBox('success','修改角色成功');
        }else{
            $url=popBox('error','修改角色失败');
        }
        $this->redirect($url);
    }

    public function delRole()//删除管理员
    {
        $id=input('post.id');
        $res=model('Admin')->where(['id'=>$id])->delete();
        if($res){
            return json(['status'=>true,'message'=>'删除成功']);
        }
        return json(['status'=>false,'message'=>'删除失败']);
    }

    public function getRoleName($id)
    {
        $role=model('Roles')->get($id);
        if($role){
            return $role->role_name;
        }
        return '暂未分配角色';
    }
}
