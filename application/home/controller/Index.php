<?php
namespace app\home\controller;

use think\Controller;
use app\admin\model\BannersModel;

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

    	return $this->fetch("index/index",["content"=>$content]);
    }

    public function videoList()//视频列表
    {
        $data='';
        $data=model('admin/Video')->where(['status'=>1])->select();
        $this->assign('data',$data);
        return view('index/videoList');
    }
}
