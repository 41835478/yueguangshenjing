<?php
namespace app\home\controller;

use think\Controller;
use app\admin\model\BannersModel;

class Index extends controller
{
    public function index()
    {
        $content = [];

        $content["banner"] = BannersModel::order("sort","desc")->select();#轮播图

    	return $this->fetch("index/index",["content"=>$content]);
    }
}
