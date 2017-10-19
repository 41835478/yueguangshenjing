<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use think\Cache;
use think\View;
class Login
{
    
    public function _initialize()
    {
        parent::_initialize();
        $this->user = new User;  //别名：避免与控制名冲突
    }

    public function register()
    {
		$post=input('param.');
		if(request()->isPost()){
			 #判断手机号
		    if (preg_match("/^(13|15)d{9}$/",$post['phone'])) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'手机验证码不符合规则']);
		    }
		    $user=db('user')->where('mobile',$post['phone'])->find();
		    if($user){
		    	return jsonp(['status'=>401,'message'=>'该手机号已经注册']);
		    }
		    if ($post['login_pwd1'] !=$post['login_pwd2']) 
		    { 
		       	return jsonp(['status'=>401,'message'=>'两次密码不一致']);
		    } 
		    #判断验证码
		    $code=Cache::get('yzm');
		    if($post['yzm']!=$code['yzm'] && $post['phone']!=$code['phone'] ){
		    	return jsonp(['status'=>401,'message'=>'手机验证码有误']);
		    }
		    #生成随机密码
		    $randpwd='';
		    for ($i = 0; $i < 5; $i++) 
			{ 
			$randpwd .= chr(mt_rand(97, 122)); 
			}
			$password=md5($post['login_pwd1'].$randpwd);
			#组合数据
			$data=[];
			$data['mobile']=$post['phone'];
			$data['unique']=$randpwd;
			$data['login_pwd']=$password;
			$data['pay_pwd']=md5($post['pay_pwd']);
			$data['created_at']=time();
			$data['updated_at']=time();
			$res=db('user')->insert($data);			
    		if($res){
    			return jsonp(['status'=>200,'message'=>'注册成功']);
    		}else{
    			return jsonp(['status'=>401,'message'=>'注册失败,请稍后再试']);
    		}			
		}		
		return view();
    }
    #登录
    public function login()
    {
    	$post=input('param.');

    	return view();
    }

    public function sendyamcode()
    {
    	$post=input('param.');
    	$yzm=111111;
    	$data=[];
    	$data['yzm']=$yzm;
    	$data['phone']=$post['phone'];
    	Cache::set('yzm',$data,600);
    	
        return jsonp(['status'=>200,'message'=>'发送成功']);
    	
    }
}
