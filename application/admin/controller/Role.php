<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Role extends Base
{
    public function index()//角色管理
    {
        $data = model('Roles')->field(['id', 'role_name','rule'])->select();
        return view('role/index', ['data' => $data]);
    }

    public function addRole()//添加角色
    {
        $date['role_name'] = input('post.role_name');
        $res = model('Roles')->save($date);
        if ($res) {
            $url = popBox('success', '添加角色成功');
        } else {
            $url = popBox('error', '添加角色失败');
        }
        $this->redirect($url);
    }

    public function roleAct(Request $request)//角色的修改和删除操作
    {
        $date = $request->post();
        if ($date['flag'] == 1) {//修改角色
            $role = model('Roles')->get($date['id']);
            $role->role_name = $date['name'];
            if ($role->save()) {
                return json(['status' => true, 'message' => '修改角色名称成功']);
            }
            return json(['status' => false, 'message' => '修改角色名称失败']);
        } else {//删除角色
            $res = model('Roles')->where(['id' => $date['id']])->delete();
            if ($res) {
                return json(['status' => true, 'message' => '删除角色成功']);
            }
            return json(['status' => false, 'message' => '删除角色失败']);
        }
    }

    public function roleNode($id)//分配权限
    {
        $data=array();
        $data=model('Nodes')->field(['id','node_name','pid'])->select();
        return view('role/roleNode',['data'=>$data,'id'=>$id]);
    }

    public function accessRule()//修改权限
    {
        $data=input('post.');
        $str='';
        if(isset($data['checkbox'])){
            foreach($data['checkbox'] as $v){
                $str.=$v.',';
            }
            $string=rtrim($str,',');
        }else{
            $string='';
        }
        $role=model('Roles')->get($data['id']);
        $role->rule=$string;
        if($role->save()){
            $url=popBox('success','分配权限成功');
        }else{
            $url=popBox('error','分配权限失败');
        }
        $this->redirect($url);
    }

    public function ruleSee()//查看权限
    {
        $id=input('post.id');
        $role=model('Roles')->get($id);
        $str='';
        if($role->rule){
            $arr=explode(',',$role->rule);
            $node_name=model('Nodes')->whereIn('id',$arr)->column('node_name');
            foreach($node_name as $v){
                $str.=$v.'&nbsp;&nbsp;';
            }
        }else{
            $str='该角色暂时没有分配权限';
        }
        return json(['status'=>true,'message'=>'获取数据成功','data'=>$str]);
    }
}
