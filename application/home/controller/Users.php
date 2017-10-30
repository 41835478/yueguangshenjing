<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use app\admin\model\Alipay;
use app\admin\model\RearviewRecordModel;
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
    	$alipay=Alipay::where('user_id',$uid)->find();
    	$this->assign('alipay',$alipay);
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

        if(strlen($post['nickname']) > 15 ){
             $this->error('昵称过长,请输入1~5个汉字');
        }

    		$data=[];
    		 	// 获取表单上传文件 
    		if(request()->file("user_pic")){
    			 $file = request()->file("user_pic");
			    // 移动到框架应用根目录/public/uploads/ 目录下
			    $info = $file->validate(['size'=>10485756,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
			    if($info){
			        // 成功上传后 获取上传信息
            //$aaa=$info[$fileName]['savepath'].$info[$fileName];
            $php_self = str_replace('\\', '/', $info->getSaveName());
			       $data['user_pic']='/uploads/'.$php_self;
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
    	if($alipay){
    		$alipay['alipay_account']=substr_replace($alipay['alipay_account'],'****',3,4);
    	}
    	
    	if(request()->isPost()){
    		$post=input('param.');
    		if($post['money'] ==0 || $post['money'] < 0){
    			return jsonp(['status'=>401,'message'=>'无效金额']);
    		}    		
    		if ($post['money'] %100 != 0){ //x为10的倍数
    			return jsonp(['status'=>401,'message'=>'提现金额必须是100的倍数']);
    		} 
    		$starttime=db('config')->where('name','starttime')->value('value');
    		$endtime=db('config')->where('name','endtime')->value('value');
    		$time=strtotime(date('Y-m-d'));

    		
    		if(time() < $starttime * 3600 + $time  || time() > $endtime * 3600 + $time ){
    			return jsonp(['status'=>401,'message'=>'提现时间为'.$starttime.'点与'.$endtime.'点之间']);   			
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
    		$data['type']=6;
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
    public function  award(){


    }

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


  	#当店面时 店面奖
  	public function storeaward(){
 
  		$uid=$this->uid;
  		$onetotalstoreaward=self::public_total(9,$uid);
  		$onestoreaward=self::public_node(9,$uid);

  		$towtotalstoreaward=self::public_total(10,$uid);
  		$towstoreaward=self::public_node(10,$uid);
  		$totalstoreaward=$onetotalstoreaward + $towtotalstoreaward;

  		$this->assign(['totalstoreaward'=>$totalstoreaward,'onestoreaward'=>$onestoreaward ,'towstoreaward'=>$towstoreaward ]);
  		return $this->fetch();
  	}
  	#当用户为代理商时 代理补助奖
  	public function subsidy(){
  		$uid=$this->uid;
  		$totalsubsidy=self::public_total(11,$uid);
  		$subsidy=self::public_node(11,$uid);
  		$this->assign(['totalsubsidy'=>$totalsubsidy,'subsidy'=>$subsidy]);
  		return $this->fetch();
  	}
  	
  	#当用户为代理商时 代理发货奖
  	public function delivery(){
  		$uid=$this->uid;
  		$totaldelivery=self::public_total(12,$uid);
  		$delivery=self::public_node(12,$uid);
  		$this->assign(['totaldelivery'=>$totaldelivery,'delivery'=>$delivery]);
  		return $this->fetch();
  	}
/*************/
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
    $res_one=[];
    $res_two=[];
    $res_three=[];
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
		$ztotal=$total+$wtotal;
		$this->assign(['wone'=>$wone,'wtwo'=>$wtwo,'wthree'=>$wthree,'one'=>$one,'two'=>$two,'three'=>$three,'wtotal'=>$wtotal,'total'=>$total,'ztotal'=>$ztotal]);
  		return $this->fetch();
	}
	#团队详情
	public function myteamdetails(){
		$post=input('param.');
		$num=$post['num'];
		$level=$post['level'];
		$zid=self::get_node($this->uid);		
		#等级
		if($post['level']==1){
			$nid=$zid[0];
		}elseif($post['level']==2){
			$nid=$zid[1];
		}elseif($post['level']==3){
			$nid=$zid[2];
		}
		#消费未消费
		if($post['type']==1){
			$user=$this->user->where('level','=',1)->whereIn('id',$nid)->select();
		}elseif($post['type']==2){
			$user=$this->user->where('level','<>',1)->whereIn('id',$nid)->select();
		}
		foreach ($user as $key => $value) {
			$user[$key]['phone']=substr_replace($value['mobile'],'****',3,4);
		}
		$this->assign('level',$level);
		$this->assign('num',$num);
		$this->assign('user',$user);
  		return $this->fetch();
	}

/*****支付宝*********************************************************************************************************************************************************************************************/
	public function bingdingalipay(){
		$user=$this->user->where('id',$this->uid)->find();
		$user['pphone']=substr_replace($user['mobile'],'****',3,4);
		if(request()->isPost()){
			$post=input('param.');

			$password=md5($post['pwd'].$user['unique']);
			if($password != $user['login_pwd']){
				return jsonp(['status'=>401,'message'=>'登录密码错误']);
			}
			$code=Cache::get('yzm');
		    if($post['yzm']!=$code['yzm'] || $user['mobile']!=$code['phone'] ){
		    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
		    }
			$data=[];
			$data['user_id']=$user['id'];
			$data['alipay_account']=$post['alipay_account'];
			$data['alipay_name']=$post['alipay_name'];
			$data['created_at']=time();
			$data['updated_at']=time();
			$res=Alipay::insert($data);
			if($res){
				return jsonp(['status'=>200,'message'=>'绑定成功']);
			}			
		}
		$this->assign('user',$user);
	 	return $this->fetch();	
	}
	public function removealipay(){
		$res=Alipay::where('user_id',$this->uid)->delete();
		if($res){
			exit('<script>alert("解除绑定支付宝成功");location.href = "/home/Users/index"</script>');
		}
	}

/*****管理收货地址*********************************************************************************************************************************************************************************************/
	public function manageaddress(){
		$address=db('address')->where('user_id',$this->uid)->select();


		$this->assign('address',$address);
		return $this->fetch();	
	}
	public function addaddress(){
		if(request()->isPost()){
			$num=db('address')->where('user_id',$this->uid)->count();
			if($num >= 5){
				return jsonp(['status'=>401,'message'=>'您的收货地址已经达到最多,请删除或修改地址']);
			}
			$post=input('param.');
			$data=[];
			if($post['di']==1){
				db('address')->where('user_id',$this->uid)->update(['default'=>2]);
				$data['default']=1;
			}
			$data['user_id']=$this->uid;
			$data['name']=$post['name'];
			$data['phone']=$post['phone'];
			$data['street']=$post['street'];
			$data['area']=$post['demo2'];
            $data['area_ids']=$post['area_ids'];
			$data['area_info']=$post['area_info'];
			$data['created_at']=time();
			$data['updated_at']=time();
			$res=db('address')->insert($data);
			if($res){
				return jsonp(['status'=>200,'message'=>'添加成功']);
			}			
		}
		return $this->fetch();	
	}
	public function defaultaddress(){
		if(request()->isPost()){
			$id=input('param.id');	
				db('address')->where('user_id',$this->uid)->update(['default'=>2]);
				$res=db('address')->where('id',$id)->update(['default'=>1]);
			if($res){
				return jsonp(['status'=>200,'message'=>'修改成功']);
			}			
		}
	}
	public function editaddress(){
		$id=input('param.id');
		$address=db('address')->where('id',$id)->find();
			if(request()->isPost()){
				$post=input('param.');	
				$data['name']=$post['name'];
				$data['phone']=$post['phone'];
				$data['street']=$post['street'];
				$data['area']=$post['demo2'];
                if(isset($post['area_ids'])&&$post['area_ids']){
                    $data['area_ids']=$post['area_ids'];
                }
				$data['area_info']=$post['area_info'];
				$data['updated_at']=time();
				$res=db('address')->where('id',$post['id'])->update($data);
				if($res){
					return jsonp(['status'=>200,'message'=>'修改成功']);
				}			
			}	
		$this->assign('address',$address);
		return $this->fetch();	
	}
	public function deladdress(){
		$id=input('param.id');
		$res=db('address')->where('id',$id)->delete();
			if($res){
				return jsonp(['status'=>200,'message'=>'删除成功']);
				}	
	}
/******修改密码********************************************************************************************************************************************************************************************/
	public function alterpassword(){

		return $this->fetch();	
	}
	public function loginpassword(){
		$user=$this->user->where('id',$this->uid)->find();
		$user['pphone']=substr_replace($user['mobile'],'****',3,4);
		if(request()->isPost()){
				$post=input('param.');	
				if ($post['login_pwd1'] !=$post['login_pwd2']) 
				    { 
				       	return jsonp(['status'=>401,'message'=>'两次密码不一致']);
				    }

        if(strlen(trim($post['login_pwd1'])) < 6 || strlen(trim($post['login_pwd1'])) > 20){
                return jsonp(['status'=>401,'message'=>'请填写6位到20位登录密码']);
            }  
        if(!preg_match("/[A-Za-z]/",$post['login_pwd1']) ||  !preg_match("/\d/",$post['login_pwd1'])){
          return jsonp(['status'=>401,'message'=>'请填写字母加数字的组合']);
        }
				    #判断验证码
				    $code=Cache::get('yzm');

				    if($post['yzm']!=$code['yzm'] || $user['mobile']!=$code['phone'] ){
				    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
				    }
				    #生成随机密码
				    $randpwd='';
				    for ($i = 0; $i < 5; $i++) 
					{ 
					$randpwd .= chr(mt_rand(97, 122)); 
					}
					$password=md5($post['login_pwd1'].$randpwd);
					$data=[];
					$data['unique']=$randpwd;
					$data['login_pwd']=$password;
					$data['updated_at']=time();
					$res=$this->user->where('mobile',$user['mobile'])->update($data);
				if($res){
					return jsonp(['status'=>200,'message'=>'修改成功']);
				}			
			}	

		$this->assign('user',$user);
		return $this->fetch();	
	}
	public function alipaypassword(){
		$user=$this->user->where('id',$this->uid)->find();
		$user['pphone']=substr_replace($user['mobile'],'****',3,4);	
		if(request()->isPost()){
				$post=input('param.');

				if ($post['login_pwd1'] !=$post['login_pwd2']) 
				    { 
				       	return jsonp(['status'=>401,'message'=>'两次密码不一致']);
				    } 
				if(strlen(trim($post['login_pwd1'])) != 6){
                		return jsonp(['status'=>401,'message'=>'请填写6位支付密码']);
            		}
				    #判断验证码
				    $code=Cache::get('yzm');				 
				    if($post['yzm']!=$code['yzm'] || $user['mobile']!=$code['phone'] ){
				    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
				    }
					$password=md5(trim($post['login_pwd1']));
					$data=[];
					$data['pay_pwd']=$password;
					$data['updated_at']=time();
					$res=$this->user->where('mobile',$user['mobile'])->update($data);
				if($res){
					return jsonp(['status'=>200,'message'=>'修改成功']);
				}			
			}

		$this->assign('user',$user);
		return $this->fetch();	
	}

/******库存******************************************************************************************************************************************************************************************/
	public function myInventory(){
    #增加
    $increaseInventory=RearviewRecordModel::where(['uid'=>$this->uid,'is_add'=>1])->select();
    // foreach ($increaseInventory as $key => $value) {
    //     $user=db('user')->where('id',$value['sourceid'])->find();
    //     $increaseInventory[$key]['name']=$user['nickname'];
    //     $increaseInventory[$key]['user_pic']=$user['user_pic'];
    //   }
    #减少
    $reducemyInventory=RearviewRecordModel::where(['uid'=>$this->uid,'is_add'=>2])->select();
    foreach ($reducemyInventory as $key => $value) {
        $user=db('user')->where('id',$value['uid'])->find();
        $reducemyInventory[$key]['name']=$user['nickname'];
        $reducemyInventory[$key]['user_pic']=$user['user_pic'];
      }
      #增加总数
      $z=RearviewRecordModel::where(['uid'=>$this->uid,'is_add'=>1])->count();
      #减少总数
      $x=RearviewRecordModel::where(['uid'=>$this->uid,'is_add'=>2])->count();

    $count=$z-$x;
    $this->assign(['increaseInventory'=>$increaseInventory,'reducemyInventory'=>$reducemyInventory,'count'=>$count]);
		return $this->fetch("users/myinventory");
	}


    #退出
    public function logout(){
    	Session::delete('uid');
    	exit('<script>alert("退出成功");location.href = "/"</script>');

    }
}


