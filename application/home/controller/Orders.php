<?php

namespace app\home\controller;

use app\admin\model\AccountRecordModel;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use think\Session;

class Orders extends Base
{
    protected $user_id;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_id=session('uid');
    }

    public function index()
    {
        $num=$totalMoney=0;
        $id=Session::get('goods_id','home');
        $num=Session::get('num','home');
        $address='';
        $find=model('admin/Address')->where(['user_id'=>session('uid'),'default'=>1])->find();
        if(!$find){
            $mod=model('admin/Address')->where(['user_id'=>session('uid')])->order('id desc')->find();
            if($mod){
                $address=$mod;
            }
        }else{
            $address=$find;
        }
        if($id){
            $goods=model('admin/Goods')->get($id);
            $totalMoney=$goods->goods_price*$num;
        }else{
            $goods='';
        }
        $this->assign('address',$address);
        $this->assign('goods',$goods);
        $this->assign('num',$num);
        $this->assign('totalMoney',$totalMoney);
        return view('orders/index');
    }

    public function create()//立即购买
    {
        $date=input('post.');
        $num=0;
        $id=Session::get('goods_id','home');
        $num=Session::get('num','home');
        if($id&&$num){
            if($date['is_shop']==1){
                if(!$date['shop_name']){
                    return json(['status'=>true,'message'=>'请填写店铺名称']);
                }
            }
            $res=$this->createOrder($id,$num,$date);
            if($res){
                return json(['status'=>true,'message'=>'下单成功']);
            }
            return json(['status'=>false,'message'=>'下单失败']);
        }else{
            return json(['status'=>false,'message'=>'请先购买产品']);
        }
    }

    public function createOrder($id,$num,$data)//生成订单
    {
        $date['user_id']=$this->user_id;
        $date['order_code']=$this->order_num();
        $date['is_shop']=$data['is_shop'];
        $date['shop_name']=$data['shop_name'];
        $date['status']=1;
        $date['address_id']=$data['address_id'];
        $address=$this->getAddress($data['address_id']);
        $date['phone']=$address->name;
        $date['phone']=$address->phone;
        $date['area_info']=$address->area.','.$address->street.','.$address->area_info;
        $date['price']=$this->getGoods($id)->goods_price;
        $date['num']=$num;
        $date['created_at']=time();
        $res=model('admin/OrderModel')->insertGetId($date);
        Db::startTrans();
        try{
            if($res){
                $orderData['order_id']=$res;
                $orderData['goods_id']=$id;
                $goods=$this->getGoods($id);
                $orderData['goods_name']=$goods->name;
                $orderData['goods_pic']=$goods->main_pic;
                $orderData['goods_price']=$goods->goods_price;
                $orderData['goods_num']=$num;
                $result=model('admin/OrderInfo')->data($orderData)->save();
                if($result){
                    session('order_id',$res,'home');
                    Session::delete('goods_id','home');
                    Session::delete('num','home');
                    Db::commit();
                    return true;
                }else{
                    throw new Exception();
                }
            }else{
                throw new Exception();
            }
        }catch(Exception $e){
            Db::rollback();
            return false;
        }
    }

    public function pay()//跳转支付页面
    {
        $totalMoney=0;
        $order_id=Session::get('order_id','home');
        if($order_id){
            $order=model('admin/OrderModel')->get($order_id);
            $totalMoney=$order->price;
        }
        $user=model('admin/User')->get($this->user_id);
        return view('orders/pay',['totalMoney'=>$totalMoney,'user'=>$user]);
    }

    public function getAddress($id)//获取地址
    {
        $address=model('admin/Address')->get($id);
        return $address;
    }

    public function getGoods($id)//获取商品
    {
        $goods=model('admin/Goods')->get($id);
        return $goods;
    }

    public function createPay()//公共的生成支付
    {
        $date=input('post.');
        $order_id=Session::get('order_id','home');
        if($date['flag']==3){//说明是余额支付
            if($order_id){
                $user=model('admin/User')->get($this->user_id);
                if(md5($date['pwd'])==$user->pay_pwd){
                    $res=$this->balancePay($order_id,$user);
                    if($res){
                        Session::delete('order_id','home');
                        return json(['status'=>true,'message'=>'支付成功']);
                    }else{
                        $message='支付失败';
                    }
                }else{
                    $message='支付密码输入错误';
                }
                return json(['status'=>false,'message'=>$message]);
            }
            return json(['status'=>false,'message'=>'该订单已支付请不要重复支付']);
        }
    }

    public function balancePay($id,$user)//余额支付
    {
        $order=model('admin/OrderModel')->get($id);
        if($user->account<$order->price){
            return false;
        }else{//只有用户中的余额大于等于订单金额才可以购买
            Db::startTrans();
            try{
                $order->status=2;
                if($order->save()){
                    $res=$this->writeRecord($order->price);
                    if($res){
                        $res2=$this->userAccountChange($order->price);
                        if($res2){
                            Db::commit();
                            return true;
                        }else{
                            throw new Exception();
                        }
                    }else{
                        throw new Exception();
                    }
                }else{
                    throw new Exception();
                }
            }catch (Exception $e){
                Db::rollback();
                return false;
            }
        }
    }

    public function writeRecord($money)//写入记录
    {
        $date['user_id']=$this->user_id;
        $date['record_info']='购买商品';
        $date['type']=AccountRecordModel::TYPE_FIVE;
        $date['status']=2;
        $date['money']=$money;
        $res=model('admin/AccountRecordModel')->data($date)->save();
        if($res){
            return true;
        }
        return false;
    }

    public function userAccountChange($money)//改变用户表
    {
        $user=model('admin/User')->get($this->user_id);
        if($user->level!=2){
            $user->level=2;
        }
        $user->account=$user->account-$money;
        if($user->save()){
            return true;
        }
        return false;
    }

    public function paySuccess()//支付成功跳转页面
    {
        return view('orders/paySuccess');
    }

    public function order_num()//生成订单号
    {
        $code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderCode = $code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderCode;
    }
}
