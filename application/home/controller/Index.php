<?php
namespace app\home\controller;

use app\admin\model\Config;
use app\admin\model\User;
use think\Controller;
use app\admin\model\BannersModel;
use think\Session;
use Service\ThreeDistribution;
use Service\SubsidyService;

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
    #检查用户n天内没有投资的直接确认收货 默认7天
    public function timeOrder(){
        $config = Config::get(19)->value;
        $tt = date('Y-m-d', strtotime('-'.$config.' days'));

        $where["status"] = 3;
        $user = User::where($where)->select();
        foreach ($user as $v){
            if($tt > date("Y-m-d H:i:s",$v["created_at"])){
                $res = User::get($v["id"]);
                $res->status = 4;
                $res1 = $res->save();
                if($res1){
                    $three = new ThreeDistribution();
                    $three->addThree($v["id"]);

                    $subsydy = new SubsidyService();
                    $subsydy->subsidy($v["id"]);
                    #理商伞下购货，额外补助200元/台（同级别不重复拿） user_id  商品数量
                    $subsydy->umbrella($v["id"]);
                    #根据店面等级进行补助  订单信息
                    $subsydy->shopLevel($v["id"]);
                    #代理商发货奖励
                    $subsydy->daili($v["id"]);

                    echo "执行成功";
                }

            }
        }
        return "执行完毕";
    }
}
