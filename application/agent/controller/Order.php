<?php

namespace app\agent\controller;

use think\Controller;
use think\Request;

class Order extends Base
{
    public function index(Request $request)
    {
        $user=$this->getStatic();
        $user_info=model('User')->get($user[1]);
        if(in_array($user_info->level,[7,8])){
            $flag=1;//店铺
            $mod=model('admin/OrderModel')->where(['shop_id'=>$user[1]]);
        }else{
            $flag=2;//代理商
            $mod=model('admin/OrderModel')->where(['send_id'=>$user[1]]);
        }
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
            $mod->whereOr('user_id','=',$user_id);
        }
        $data=$mod->order('created_at','desc')->paginate(config('page'),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        $page=$this->getPage($data);
        $this->assign('currentPage',$page['currentPage']);
        $this->assign('total',$page['total']);
        $this->assign('data', $data);
        $this->assign('flag',$flag);
        $this->assign('render', $page['page']);
        return view('orders/index');
    }

    public function orderInfo($id)//查看商品详情
    {
        $data=model('admin/OrderInfo')->where(['order_id'=>$id])->select();
        return view('orders/orderInfo',['data'=>$data]);
    }

    public function sendGoods()//发货
    {
        $id=input('post.id');
        $order=model('admin/OrderModel')->get($id);
        if(!$order->logistics_num){
            return json(['status'=>false,'message'=>'请填写物流单号']);
        }else{
            $order->status=3;
            if($order->save()){
                return json(['status'=>true,'message'=>'发货成功']);
            }
            return json(['status'=>false,'message'=>'发货失败']);
        }
    }

    public function cour()//填写物流单号
    {
        $logistics_num=input('post.logistics_num');
        $id=input('post.id');
        $order=model('admin/OrderModel')->get($id);
        $order->logistics_num=$logistics_num;
        if($order->save()){
            $url=popBox('success','添加物流单号成功');
        }else{
            $url=popBox('error','添加物流单号失败');
        }
        $this->redirect($url);
    }

    public function getUser($data,$flag)
    {
        if($flag==1){//说明是用户手机号查询
            return model('User')->where(['mobile'=>$data])->value('id');
        }else{
            return model('User')->where('name','like','%'.$data.'%')->value('id');
        }
    }

    public function getStatic()
    {
        return self::getUserInfo();
    }

    static private function getUserInfo()
    {
        $res=model('User')->getDecrypt(session('info','','agent'));
        if($res){
            $arr=explode('-',$res);
            return $arr;
        }else{
            return false;
        }
    }
}
