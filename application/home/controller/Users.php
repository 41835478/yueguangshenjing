<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Alipay;
use think\Cache;
use think\Session;
use think\File;
class Users extends Base
{
	
	public function _initialize()
    {
        parent::_initialize();
        $this->user = new User;
        $this->uid=Session::get('uid');

    }

    public function index()
    {
    	$uid=Session::get('uid');
    	$user=$this->user->where('id',$uid)->find();
    	$user['phone']=substr_replace($user['mobile'],'****',3,4);
    	if(!$user['nickname']){
    		$user['nickname']='暂无';
    	}
    	if(!$user['pid']){
    		$user['pphone']='系统';
    	}else{
    		$user['pphone']=$this->user->where('id',$user['pid'])->value('mobile');
    	}

    	$this->assign('user',$user);
    	return $this->fetch();
    }
    #修改个人资料
    public function editdata()
    {
    	$uid=Session::get('uid');
    	$user=$this->user->where('id',$uid)->find();
    	if(request()->isPost()){
    		$post=input('param.');
    		$data=[];
    		 	// 获取表单上传文件 
    		if(!empty(request()->file("user_pic"))){
    			 $file = request()->file("user_pic");
			    // 移动到框架应用根目录/public/uploads/ 目录下
			    $info = $file->validate(['size'=>10485756,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
			    if($info){
			        // 成功上传后 获取上传信息
			        $data['user_pic']='/uploads/'.$info->getSaveName();
			    }else{
			        // 上传失败获取错误信息
			        $error= $file->getError() ;
			        $this->error("$error");
			    } 
			    }  		
	    		$data['nickname']=$post['nickname'];   
	    		$data['sex']=$post['sex'];
	    		$data['updated_at']=time();
	    		$res=$this->user->where('id',$uid)->update($data);
	    		 if($res){
		            $this->success('修改成功', 'Users/index');
		        } else {
		            $this->error('修改失败');
		        }
    	}
    	$user=$this->user->where('id',$uid)->find();
    	$user['mobile']=substr_replace($user['mobile'],'****',3,4);
    	$this->assign('user',$user);
    	return $this->fetch();
    }

    /****账户模块*************************************************************************************************************************************************************************************************************************************************************************************************/
    #我的账户
    public function myaccount(){
    	$uid=Session::get('uid');
    	$user=$this->user->where('id',$uid)->find();

    	$this->assign('user',$user);
    	return $this->fetch();
    }
    #提现
    public function withdrawal(){
    	$uid=Session::get('uid');
    	$user=$this->user->where('id',$uid)->find();
    	#查询支付宝
    	$alipay=Alipay::where('user_id',$uid)->find();
    	$alipay['alipay_account']=substr_replace($alipay['alipay_account'],'****',3,4);
    	if(request()->isPost()){
    		$post=input('param.');
    		if($post['money'] ==0 || $post['money'] < 0){
    			return jsonp(['status'=>401,'message'=>'无效金额']);
    		}    		
    		if ($post['money'] %100 != 0){ //x为10的倍数
    			return jsonp(['status'=>401,'message'=>'提现金额必须是100的倍数']);
    		} 
    		if($post['money'] > $user['account']){
    			return jsonp(['status'=>401,'message'=>'余额不足']);
    		}
    		if(md5($post['pay_pwd']) != $user['pay_pwd']){
    			return jsonp(['status'=>401,'message'=>'支付密码错误']);
    		}
    		#查询配置
    		$lmoney=abs($post['money']);
    		$shouxu=db('config')->where('name','withdrawal')->value('value');
    		$shouxu=1 - $shouxu/100;
    		$smoney= $lmoney *  $shouxu ;   		
    		#组合数据
    		// 启动事务
Db::startTrans();
try{
 			$res=$this->user->where('id',$uid)->setDec('account',$lmoney);
 			#插入记录表
    		$data=[];
    		$data['user_id']=$uid;
    		$data['record_info']='提现';
    		$data['type']=7;
    		$data['status']=2;
    		$data['money']=$lmoney;
    		$data['created_at']=time();
    		$data['updated_at']=time();
    		$ress=db('account_record')->insert($data);
    		#插入提现申请
    		$dttt=[];
    		$dttt['user_id']=$uid;
    		$dttt['money']=$lmoney;
    		$dttt['reality_money']=$smoney;
    		$dttt['alipay_account']=$alipay['alipay_account'];
    		$dttt['alipay_num']='YY'.uniqid(true).rand(10000,99999);
    		$dttt['status']=1;
    		$dttt['created_at']=time();
    		$dttt['updated_at']=time();
    		$resss=db('withdrawals')->insert($dttt);
    		if($res && $ress && $resss){
    			    // 提交事务
    				Db::commit();
    				return jsonp(['status'=>200,'message'=>'提现申请已提交,请耐心等待审核']);
    		}
    
} catch (\Exception $e) {
    // 回滚事务
    Db::rollback();
    return jsonp(['status'=>401,'message'=>$e->getMessage()]);
}

    	}
    	$this->assign('alipay',$alipay);
    	$this->assign('user',$user);
    	return $this->fetch();
    }

	#收入明细
	public function incomedetails(){
		$incomedetails=db('account_record')->where(['user_id'=>$this->uid,'status'=>1])->select();

		$this->assign('incomedetails',$incomedetails);
		return $this->fetch();
	}
	#支出明细
	public function expendituredetails(){
		$expendituredetails=db('account_record')->where(['user_id'=>$this->uid,'status'=>2])->select();

		$this->assign('expendituredetails',$expendituredetails);
		return $this->fetch();
	}
	#提现记录
	public function withdrawaldetails(){
		$withdrawaldetails=db('withdrawals')->where(['user_id'=>$this->uid])->select();

		$this->assign('withdrawaldetails',$withdrawaldetails);
		return $this->fetch();
	}

  /****奖金*******************************************************************************************************************************************************************************************/
  	#分销奖金
  	public function distributionaward(){
  		#查询下级id
  		$zid=self::get_node($this->uid);
  		$uid=$this->uid;
  		$zzid=self::get_nodetotal([$uid],0,3,[]);
  		$totaldistribution=db('account_record')->where('type',1)->whereIn('sourceid',$zzid)->sum('money');
  		#查询一级分销
  		$onedistribution=db('account_record')->where('type',1)->whereIn('sourceid',$zid[0])->select();
  		foreach ($onedistribution as $key => $value) {
  				$user=$this->user->where('id',$value['sourceid'])->find();
  				$onedistribution[$key]['name']=$user['nickname'];
  				$onedistribution[$key]['user_pic']=$user['user_pic'];
  		}
  		#查询二级分销
  		$twodistribution=db('account_record')->where('type',1)->whereIn('sourceid',$zid[1])->select();
  		foreach ($twodistribution as $key => $value) {
  				$user=$this->user->where('id',$value['sourceid'])->find();
  				$twodistribution[$key]['name']=$user['nickname'];
  				$twodistribution[$key]['user_pic']=$user['user_pic'];
  		}
  		#查询三级分销
  		$threedistribution=db('account_record')->where('type',1)->whereIn('sourceid',$zid[2])->select();
  		foreach ($threedistribution as $key => $value) {
  				$user=$this->user->where('id',$value['sourceid'])->find();
  				$threedistribution[$key]['name']=$user['nickname'];
  				$threedistribution[$key]['user_pic']=$user['user_pic'];
  		}
  		$this->assign('totaldistribution',$totaldistribution);
  		$this->assign('onedistribution',$onedistribution);
  		$this->assign('twodistribution',$twodistribution);
  		$this->assign('threedistribution',$threedistribution);
  		return $this->fetch();
  	}
  	#招商升级奖
  	public function upgradeaward(){
  		$uid=$this->uid;
  		$totalupgradeaward=self::public_total(2,$uid);
  		$upgradeaward=self::public_node(3,$uid);
  		$this->assign('totalupgradeaward',$totalupgradeaward);
  		$this->assign('upgradeaward',$upgradeaward);
  		return $this->fetch();
  	}
  	#招商销售奖
  	public function salesaward(){
  		$uid=$this->uid;
  		$totalsalesaward=self::public_total(3,$uid);
  		$salesaward=self::public_node(3,$uid);
  		$this->assign('totalsalesaward',$totalsalesaward);
  		$this->assign('salesaward',$salesaward);
  		return $this->fetch();
  	}
  	#安装红包
  	public function installred(){
  		$uid=$this->uid;
  		$totalinstallred=self::public_total(4,$uid);
  		$installred=self::public_node(4,$uid);
  		$this->assign('totalinstallred',$totalinstallred);
  		$this->assign('installred',$installred);
  		return $this->fetch();
  	}

  	#公用方法获取奖总额
  	public static function public_total($type,$uid){
  		$total=db('account_record')->where(['type'=>$type,'user_id'=>$uid])->sum('money'); 		
  		return $total;
  	}
  	#公用方法获取奖信息
  	public static function public_node($type,$uid){
  		$installred=db('account_record')->where(['type'=>$type,'user_id'=>$uid])->select();
  		foreach ($installred as $key => $value) {
  			$user=db('user')->where('id',$value['sourceid'])->find();
  			$installred[$key]['name']=$user['nickname'];
  			$installred[$key]['user_pic']=$user['user_pic'];
  		}
  		return $installred;
  	}



/*************************************************************************************************/
	#查询三级会员
  	public static function get_nodetotal($id,$num,$number,$userId)
    {
         if ($num == $number) {
           return $userId;
         }
        $ids = User::whereIn('pid',$id)->column('id');
        if (count($ids) < 1) {
            return $userId;
        }
        $userId =array_merge_recursive($userId,$ids); //查询全部
       
        $num ++ ;
        return self::get_nodetotal($ids,$num,$number,$userId);
    }


	# 分开获取下级(1.2.3)
	protected static function get_node($uid){
		# 获取下级的(一级)ids

		$one = db('user') -> field('id') -> where(['pid'=>$uid]) ->select();
		$two = [];
		$three = [];
		# 获取二级用户
		foreach($one as $key=>$value){
			$tmp = db('user') -> field('id') -> where(['pid'=>$value['id']]) ->select();
			if($tmp){
				foreach ($tmp as $value) {
					$two[] = $value;
				}
			}
		}
		# 获取三级用户
		foreach ($two as $key => $value) {
			$tmp = db('user') -> field('id') -> where(['pid'=>$value['id']]) ->select();
			if($tmp){
				foreach ($tmp as $value) {
					$three[] = $value;
				}
			}
		}
		# 处理数据
		foreach ($one as $key => $value) {
			$res_one[] = $value['id'];
		}
		foreach ($two as $key => $value) {
			$res_two[] = $value['id'];
		}
		foreach ($three as $key => $value) {
			$res_three[] = $value['id'];
		}
		# 返回一级,二级,三级,
		return [$res_one,$res_two,$res_three];
	}


/*****我的团队*********************************************************************************************************************************************************************************************/
	public function myteam(){
		$zid=self::get_node($this->uid);
		$zzid=self::get_nodetotal($this->uid,0,3,[]);
		#查询未消费一级会员
		$wone=$this->user->where('level','=',1)->whereIn('id',$zid[0])->count();
		#查询未消费二级会员
		$wtwo=$this->user->where('level','=',1)->whereIn('id',$zid[1])->count();
		#查询未消费三级会员
		$wthree=$this->user->where('level','=',1)->whereIn('id',$zid[2])->count();

		#查询消费一级会员
		$one=$this->user->where('level','<>',1)->whereIn('id',$zid[0])->count();
		#查询消费二级会员
		$two=$this->user->where('level','<>',1)->whereIn('id',$zid[1])->count();
		#查询消费三级会员
		$three=$this->user->where('level','<>',1)->whereIn('id',$zid[2])->count();

		$wtotal=$this->user->where('level','=',1)->whereIn('id',$zzid)->count();
		$total=$this->user->where('level','<>',1)->whereIn('id',$zzid)->count();
		$this->assign(['wone'=>$wone,'wtwo'=>$wtwo,'wthree'=>$wthree,'one'=>$one,'two'=>$two,'three'=>$three,'wtotal'=>$wtotal,'total'=>$total]);
  		return $this->fetch();
	}

/**************************************************************************************************************************************************************************************************/
	



    #退出
    public function logout(){
    	Session::delete('uid');

    	exit('<script>alert("退出成功");location.href = "/"</script>');

    }
}


