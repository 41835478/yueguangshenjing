<?php
namespace app\home\controller;

use think\Controller;
use app\admin\model\ContentsModel;

class Content extends controller
{
    public function index()
    {
        #平台介绍
        $content = ContentsModel::get(1);
        return $this->fetch("content/aboutUs",["content"=>$content]);
    }
    public function beginnerGuide()
    {
        #新手指南
        return $this->fetch("content/beginnerGuide");
    }
    public function registerRules()
    {
        #注册规则
        return $this->fetch("content/registerRules");
    }
    public function cashRules()
    {
        #提现规则
        return $this->fetch("content/cashRules");
    }
    public function notice()
    {
        #最新公告
        return $this->fetch("content/notice");
    }
    public function noticeDetails()
    {
        #最新公告
        return $this->fetch("content/noticeDetails");
    }
    public function kefu()
    {
        #客服
        return $this->fetch("content/kefu");
    }
}
