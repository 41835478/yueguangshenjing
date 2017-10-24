<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Orders extends Base
{
    public function index(Request $request)
    {
        $mod=model('OrderModel');
        if($request->has('status','param',true)){
            $mod->where(['status'=>$request->param('status')]);
        }
        if($request->has('flag','param',true)){
            $mod->where(['flag'=>$request->param('flag')]);
        }
        if($request->has('mobile','param',true)){
            $user_id=$this->getUserInfo($request->param('mobile'),1);
            $mod->where(['user_id'=>$user_id]);
        }
        if($request->has('name','param',true)){
            $user_id=$this->getUserInfo($request->param('name'),2);
            $mod->where(['user_id'=>$user_id]);
        }
        $data=$mod->paginate(config('page'),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $page=$this->getPage($data);
        $this->assign('currentPage',$page['currentPage']);
        $this->assign('total',$page['total']);
        $this->assign('data', $data);
        $this->assign('render', $page['page']);
        return view('orders/index');
    }

    public function orderInfo($id)//查看商品详情
    {
        $data=model('OrderInfo')->where(['order_id'=>$id])->select();
        return view('orders/orderInfo',['data'=>$data]);
    }

    public function sendGoods()//发货
    {
        $id=input('post.id');
        $order=model('OrderModel')->get($id);
        $order->status=3;
        if($order->save()){
            return json(['status'=>true,'message'=>'发货成功']);
        }
        return json(['status'=>false,'message'=>'发货失败']);
    }

    public function cour()//填写物流单号
    {
        $logistics_num=input('post.logistics_num');
        $id=input('post.id');
        $order=model('OrderModel')->get($id);
        $order->logistics_num=$logistics_num;
        if($order->save()){
            $url=popBox('success','添加物流单号成功');
        }else{
            $url=popBox('error','添加物流单号失败');
        }
        $this->redirect($url);
    }

    public function getUserInfo($data,$flag)
    {
        if($flag==1){//说明是用户手机号查询
            return model('User')->where(['mobile'=>$data])->value('id');
        }else{
            return model('User')->where('name','like','%'.$data.'%')->value('id');
        }
    }
}
