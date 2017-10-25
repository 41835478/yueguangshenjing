<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\ContentsModel;
use think\Paginator;

class Contents extends Base
{
    /**
     * 显示资源列表
     *  $type 1平台介绍 2新手指南 3最新公告 4客服中心
     * @return \think\Response
     */
    public function index($type)
    {
        //
        if($type == "1"){
            $contentOne = ContentsModel::get(1);#平台简介
            return $this->fetch("contents/introduce",["contentOne"=>$contentOne]);
        }elseif ($type == "2"){
            $guide = ContentsModel::all(function($query){
                $query->where(["type"=>2])->order("created_at","desc");
            });#新手指南
            $guide = $this->strContent($guide);
            return $this->fetch("contents/guide",["guide"=>$guide]);
        }elseif ($type == "3"){
            $notice = ContentsModel::all(function($query){
                $query->where(["type"=>3])->order("created_at","desc");
            });#最新公告
            $notice = $this->strContent($notice);
            return $this->fetch("contents/notice",["notice"=>$notice]);
        }elseif ($type == "4"){
            $service = ContentsModel::get(2);#客服中心
            return $this->fetch("contents/service",["service"=>$service]);
        }
    }
    public function strContent($content){
        foreach ($content as $v){
            $v->content = mb_substr(strip_tags($v->content),0,80,"utf-8");
        }
        return $content;
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($type)
    {
        //
        if($type == "3"){
            return $this->fetch("contents/addnotice");
        }
        if($type == "2"){
            return $this->fetch("contents/addguide");
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $input = Request::instance()->only("content,title,type");

        if(empty($input["title"]) && isset($input["title"])){
            $Url=popBox('error','标题不能为空!');
            $this->redirect($Url);
        }
        if(empty($input["content"]) && isset($input["content"])){
            $Url=popBox('error','内容不能为空!');
            $this->redirect($Url);
        }

        $content = new ContentsModel();
        $content->data($input);
        $content->save();

        if($content->type == "2"){
            return $this->index(2);
        }

        if($content->type == "3"){
            return $this->index(3);
        }

    }
    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $content = ContentsModel::get($id);
        if($content->type == "2"){
            return $this->fetch("contents/editguide",["content"=>$content]);
        }
        if($content->type == "3"){
            return $this->fetch("contents/editnotice",["content"=>$content]);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if($id == "1"){
            $input = Request::instance()->only("content");
        }elseif ($id == "2"){
            $input = Request::instance()->only("content,telephone,worktime");
        }else{
            $input = Request::instance()->only("content,title");
        }

        if(empty($input["content"])){
            $Url=popBox('error','内容不能为空!');
            $this->redirect($Url);
        }
        if(empty($input["telephone"]) && isset($input["telephone"])){
            $Url=popBox('error','电话不能为空!');
            $this->redirect($Url);
        }
        if(empty($input["worktime"]) && isset($input["worktime"])){
            $Url=popBox('error','内工作时间不能为空!');
            $this->redirect($Url);
        }

        $content = new ContentsModel();
        $content->save($input,["id"=>$id]);
        $Url=popBox('success','更改成功');
        $this->redirect($Url);

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $content = ContentsModel::get($id);
        $content->delete();

        $Url = popBox('success', '删除成功!');
        $this->redirect($Url);
    }
}
