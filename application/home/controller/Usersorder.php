<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Alipay;
use think\Cache;
use think\Session;
use think\File;
use app\admin\model\OrderModel;
use app\admin\model\OrderInfo;
use app\admin\model\Goods;
class Usersorder extends Base
{
	public function _initialize()
    {
        parent::_initialize();
        $this->Order = new OrderModel;
        $this->OrderInfo = new OrderInfo;
        $this->Goods = new Goods;
        $this->uid=Session::get('uid');

    }

	public function orderindex(){
		$type=input('param.type');
		#1 代付,2待发 3待收货,4已完成
		$order=self::userorder($type,$this->uid);
		
		$this->assign(['order'=>$order,'type'=>$type]);
		return $this->fetch();
	}

	#查询订单方法
	public	static function userorder($type,$uid){
		$orderinfo=db('order')->where(['status'=>$type,'user_id'=>$uid])->select();		
		foreach ($orderinfo as $k => $v ){
			$orderinfo[$k]['created_at']=date('Y-m-d H:i:s',$v['created_at']);
					$info=db('order_info')->where('order_id',$v['id'])->select();
				$orderinfo[$k]['info']=$info;
		}
		return $orderinfo;
	}
	#订单详情
	public function orderinfo(){
		$order_id=input('param.order_id');
		$orderinfo=$this->Order->where(['id'=>$order_id])->find();
			$info=$this->OrderInfo->where('order_id',$orderinfo['id'])->select();
			$orderinfo['info']=$info;
		//$orderinfo['created_at']=date('Y-m-d H:i:s',$orderinfo['created_at']);
		$this->assign(['orderinfo'=>$orderinfo]);
		return $this->fetch();
	}
	#取消订单
	public function delorder(){
		if(request()->isPost()){
			$order_id=input('param.order_id');

			$orderinfo=$this->Order->where(['id'=>$order_id])->delete();
			$info=$this->OrderInfo->where('order_id',$order_id)->delete();
			if($orderinfo && $info){
				return jsonp(['status'=>200,'message'=>'删除成功']);
			}else{
				return jsonp(['status'=>401,'message'=>'重试']);
			}
		}
	}
	#确认收货
	public function stocks(){
		if(request()->isPost()){
			$order_id=input('param.order_id');
			$orderinfo=$this->Order->where(['id'=>$order_id])->update(['status'=>4]);
			if($orderinfo){
				return jsonp(['status'=>200,'message'=>'执行成功']);
			}else{
				return jsonp(['status'=>401,'message'=>'重试']);
			}
		}
	}

}