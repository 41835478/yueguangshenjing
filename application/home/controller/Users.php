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
    	$alipay['alipay_account']=
    	
    	$this->assign('alipay',$alipay);
    	$this->assign('user',$user);
    	return $this->fetch();
    }

  /*****************************************************************************************************************************************************************************************************************************************************************************************************/

    #退出
    public function logout(){
    	Session::delete('uid');

    	exit('<script>alert("退出成功");location.href = "/"</script>');

    }
}


