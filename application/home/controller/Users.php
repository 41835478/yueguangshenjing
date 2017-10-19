<?php
namespace app\home\controller;
use think\Controller;
use app\admin\model\User;
use think\Cache;
use think\Session;
class Users extends Controller
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
    public function editdata(){
    	$uid=Session::get('uid');
    	$user=$this->user->where('id',$uid)->find();
    	if(request()->isPost()){
    		$post=input('param.');
    		$data=[];
    		$data['nickname']=$post['nickname'];
    		$data['nickname']=$post['nickname'];
    		$data['nickname']=$post['nickname'];
    		$this->user->where('id',$uid)->update();
    	}
    	$user=$this->user->where('id',$uid)->find();
    	$user['mobile']=substr_replace($user['mobile'],'****',3,4);
    	$this->assign('user',$user);
    	return $this->fetch();
    }





}


