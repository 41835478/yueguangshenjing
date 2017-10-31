<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Address extends Base
{
    public function index()
    {
        $data=array();
        $data=model('admin/Address')->where(['user_id'=>session('uid')])->select();
        return view('address/index',['data'=>$data]);
    }

    public function manageAddress()//管理收货地址
    {
        $data=array();
        $data=model('admin/Address')->where(['user_id'=>session('uid')])->select();
        return view('address/manageAddress',['data'=>$data]);
    }

    public function addressCreate()//加载添加收货地址
    {
        return view('address/addressCreate');
    }

    public function addAddress()//执行添加收货地址操作
    {
        $date=input('post.');
        $validate=validate('Address');
        if(!$validate->check($date)){
            return json(['status'=>false,'message'=>$validate->getError()]);
        }else{
            $date['user_id']=session('uid');
            $address=model('admin/Address')->where(['user_id'=>session('uid'),'default'=>1])->find();
            if($address){
                $address->default=2;
                $address->save();
            }
            $res=model('admin/Address')->data($date)->save();
            if($res){
                return json(['status'=>true,'message'=>'添加收货地址成功']);
            }
            return json(['status'=>false,'message'=>'添加收货地址失败']);
        }
    }

    public function delAddress()//删除地址
    {
        $id=input('post.id');
        $res=model('admin/Address')->where(['id'=>$id])->delete();
        if($res){
            return json(['status'=>true,'message'=>"删除成功"]);
        }
        return json(['status'=>false,'message'=>'删除失败']);
    }
    public function orderAddress($id)//删除地址
    {
        $id=input('get.id');
        $res=model('admin/Address')->where(['id'=>$id])->find();
        session("addre",$res);
        return json(['status'=>0]);
    }
}
