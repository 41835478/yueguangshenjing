<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Alipay;
use think\Cache;
use think\Session;
use service\Qrcode;
use app\admin\model\OrderModel;
use app\admin\model\OrderInfo;
use app\admin\model\Goods;
use Vendor\phpqrcode\phpqrcode;
vendor('phpqrcode.phpqrcode');
use Service\SubsidyService;
use Service\ThreeDistribution;
class Usersorder extends Base
{
	
//vendor('AliPay.AlipayTradeService');
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
		$orderinfo=db('order')->where(['status'=>$type,'user_id'=>$uid])->order('id desc')->select();		
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
			$three = new ThreeDistribution();
			$three->addThree($order_id);

			$subsydy = new SubsidyService();
			$subsydy->subsidy($order_id);
          #理商伞下购货，额外补助200元/台（同级别不重复拿） user_id  商品数量
			$subsydy->umbrella($order_id);
          #根据店面等级进行补助  订单信息
			 $subsydy->shopLevel($order_id);
          #代理商发货奖励
			$subsydy->daili($order_id);
			
			if($orderinfo){
				return jsonp(['status'=>200,'message'=>'收货成功']);
				}else{
			return jsonp(['status'=>401,'message'=>'重试']);
			}
		}
	}

	/******客户订单***************************************************************************************/
	public function  clientsindex(){
		$type=input('param.type');
		#1 代付,2待发 3待收货,4已完成
		$clients=self::clientsorder($type,$this->uid);
		//dump($clients);
		$this->assign(['clients'=>$clients,'type'=>$type]);
		return $this->fetch();
		
	}
	#查询客户订单方法
	public	static function clientsorder($type,$uid){
		$orderinfo=db('order')->where(['status'=>$type,'shop_id'=>$uid])->order('id desc')->select();
			
		foreach ($orderinfo as $k => $v ){
			$orderinfo[$k]['created_at']=date('Y-m-d H:i:s',$v['created_at']);
					$info=db('order_info')->where('order_id',$v['id'])->select();
				$orderinfo[$k]['info']=$info;
		}
		return $orderinfo;

	}
	public function clientsorderinfo(){
		$order_id=input('param.order_id');
		$orderinfo=$this->Order->where(['id'=>$order_id])->find();
		$info=$this->OrderInfo->where('order_id',$orderinfo['id'])->select();
		$orderinfo['info']=$info;
		$orderinfo['shop_name']=db('user')->where('id',$this->uid)->value('nickname');
		$this->assign(['orderinfo'=>$orderinfo]);
		return $this->fetch();		
	}

/***二维码***********************/
	public function qrcode(){

		$user=User::where('id',$this->uid)->find();

		if($user['user_pic']=='' || $user['nickname']==''){
			exit('<script>alert("资料不全,请先完善资料");location.href = "/home/Users/editdata"</script>');
		}


		if($user['nickname']==''){
			$user['nickname']='用户名'.$user['mobile'];
		}
		//$user['user_pic']='http://'.$_SERVER['HTTP_HOST'].$user['user_pic'];
		//dump($user['user_pic']);die;
		$this->assign(['user'=>$user]);
		return $this->fetch();
	}

	/**
	 * 生成二维码
	 * @param int $id 
	 * @param $level 容错等级
	 * @param $size 图片大小
	 * @return 
	 */
	 public function createqrcode()
	 {   
        ob_clean();  
        $object = new \ QRcode();
        $isUser = User::find($this->uid);
        $shop='';
        if($isUser->level == "7" || $isUser->level == "8"){
            $shop = $isUser->id;
        }
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/home/Login/register/pid/'.$this->uid."?shop=".$shop;//网址或者是文本内容
        $level=3;
        $size=4;
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小       
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2); 
        DIE();   
    }
}