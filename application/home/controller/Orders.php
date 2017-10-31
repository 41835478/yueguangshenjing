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

    public function index(Request $request)
    {
        $num=$totalMoney=0;
        $shop_id=$request->param('shop',0,'intval');
        if($shop_id){//说明是扫描店面二维码购买
            $user=model('admin/User')->get($shop_id);
            if($user){
                $shop_name=$user->shop_name;
            }else{
                $shop_name='';
            }
        }else{
            $shop_name='';
        }
        $id=Session::get('goods_id','home');
        $num=Session::get('num','home');
        $address='';
        if(Session::get('addre')){
            $address = Session::get('addre');
        }else{
            $find=model('admin/Address')->where(['user_id'=>session('uid'),'default'=>1])->find();
            if(!$find){
                $mod=model('admin/Address')->where(['user_id'=>session('uid')])->order('id desc')->find();
                if($mod){
                    $address=$mod;
                }
            }else{
                $address=$find;
            }
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
        $this->assign('shop_id',$shop_id);
        $this->assign('shop_name',$shop_name);
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
//            if($date['is_shop']==1){
//                if(!$date['shop_name']){
//                    return json(['status'=>true,'message'=>'请填写店铺名称']);
//                }
//            }
            $res=$this->createOrder($id,$num,$date);
            if($res){
                Session::delete('addre');
                return json(['status'=>true,'message'=>'下单成功']);
            }
            return json(['status'=>false,'message'=>'下单失败']);
        }else{
            return json(['status'=>false,'message'=>'请先购买产品']);
        }
    }

    public function createOrder($id,$num,$data)//生成订单(需要判断库存是否充足，如果库存不足则订单给平台)
    {
        $mark=1;
        $user_id='';
        $date['user_id']=$this->user_id;
        $date['order_code']=$this->order_num();
        $date['is_shop']=$data['is_shop'];
        $date['shop_name']=$data['shop_name'];
        $date['shop_id']=$data['shop_id'];
        $date['status']=1;
        $date['address_id']=$data['address_id'];
        $address=$this->getAddress($data['address_id']);
        $send_id=$this->getAgentId($address->area_ids);
        if($send_id||$data['shop_id']){
            if($data['shop_id']){
                if($this->checkStock($data['shop_id'])){
                    $mark=2;
                    $date['sign']=2;
                    $user_id=$data['shop_id'];
                }else{//如果是购买的店面但是库存不足则订单归平台
                    $data['shop_id']=0;
                    $date['type']=1;
                }
            }elseif($send_id&&!$data['shop_id']){
                if($this->checkStock($data['shop_id'])){
                    $mark=2;
                    $date['send_id']=$send_id;
                    $date['sign']=1;
                    $user_id=$data['shop_id'];
                }else{//如果购买的代理商但是库存不足则订单归平台
                    $data['send_id']='';
                    $date['type']=1;
                }
            }
        }else{
            $date['type']=1;
        }
        $date['area_ids']=$address->area_ids;
        $date['name']=$address->name;
        $date['phone']=$address->phone;
        $date['area_info']=$address->area.','.$address->street.','.$address->area_info;
        $date['price']=$this->getGoods($id)->goods_price*$num;
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
                    if($mark==2){
                        $result2=$this->getStock($user_id,$num);
                        if(!$result2){
                            throw new Exception();
                        }
                    }
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

    public function checkStock($id)
    {
        $mod=model('RearviewModel')->where(['uid'=>$id])->find();
        if($mod->repertorys>0){
            return true;
        }
        return false;
    }

    public function getStock($id,$num)//判断库存同时减掉库存  如果flag等于1则是店面id,如果flag==2则是代理商id
    {
        $mod=model('RearviewModel')->where(['uid'=>$id])->find();
        if($mod->repertorys){
            $mod->repertorys=$mod->repertorys-1;
            $mod->shipment=$mod->shipment+1;
            if($mod->save()){
                $date['uid']=$id;
                $date['is_add']=2;
                $date['info']='销售了'.$num.'台产品';
                $date['num']=$num;
                $res=model('ReariewRecordModel')->data($date)->save();
                if($res){
                    return true;
                }
            }
        }
        return false;
    }

    public function getAgentId($area_ids)//生成订单时得到该订单属于谁的订单
    {
        $arr=explode(',',$area_ids);
        $count=count($arr);
        if($count==3){
            $district=model('admin/User')->where(['district'=>$arr[2]])->where('level','in',[3,4,5,6])->value('id');
            if(!$district){
                $city=model('admin/User')->where(['city'=>$arr[1]])->where('level','in',[3,4,5,6])->value('id');
                if($city){
                    return $city;
                }
                return false;
            }
            return $district;
        }
    }

    public function pay()//跳转支付页面
    {
        $totalMoney=0;
        $getId=input('param.order_id',0,'intval');
        if($getId){
            $order_id=$getId;
        }else{
            $order_id=Session::get('order_id','home');
        }
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
                $order->flag=3;
                if($order->save()){
                    $res=$this->writeRecord($order->price);
                    if($res){
                        $res2=$this->userAccountChange($order->price);
                        if($res2){
                            $serviceDb=model('InstallFeeService','service');
                            $res3=$serviceDb->index($id);
                            if($res3){//安装红包奖励
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
