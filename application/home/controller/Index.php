<?php
namespace app\home\controller;

use app\admin\model\User;
use think\Controller;
use app\admin\model\BannersModel;
use think\Session;

class Index extends controller
{
    public function index()
    {
    	return $this->fetch("index/start");
    }
    public function content()
    {
        $content = [];

        $content["banner"] = BannersModel::order("sort","desc")->select();#轮播图
        $uid = Session::get("uid");
        
        return $this->fetch("index/index",["content"=>$content,"user"=>User::get($uid)]);
    }

    public function videoList()//视频列表
    {
        $data='';
        $data=model('admin/Video')->where(['status'=>1])->select();
        $this->assign('data',$data);
        return view('index/videoList');
    }
}
