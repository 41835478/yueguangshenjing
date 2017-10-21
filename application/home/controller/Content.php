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
        $content = ContentsModel::all(["type"=>2]);
        return $this->fetch("content/beginnerGuide",["content"=>$content]);
    }
    public function registerRules($id)
    {
        #新手指南详情
        $conDetail = ContentsModel::get($id);
        return $this->fetch("content/registerRules",["conDetail"=>$conDetail]);
    }
    public function notice()
    {
        #最新公告
        $notice = ContentsModel::all(["type"=>3]);
        return $this->fetch("content/notice",["notice"=>$notice]);
    }
    public function noticeDetails($id)
    {
        #公告详情
        $noticeDetail = ContentsModel::get($id);
        return $this->fetch("content/noticeDetails",["noticeDetail"=>$noticeDetail]);
    }
    public function kefu()
    {
        #客服
        $service = ContentsModel::get(2);#客服中心
        $service->content = strip_tags($service->content);
        return $this->fetch("content/kefu",["service"=>$service]);
    }
}
