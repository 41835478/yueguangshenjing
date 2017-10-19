<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\BannersModel;

class Banners extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        dump("轮播图列表");
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch("banners/add");  
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
        $input = input("param.");
        if(!isUrl($input["links"]) && $input["links"] != "#"){
            $Url=popBox('error','链接不合法!');
            $this->redirect($Url);
        }


        if(!$input["img"]){
            $Url=popBox('error','图片不能为空!');
            $this->redirect($Url);
        }

        $input["sort"]= $input["sort"] == "" ? 0 : $input["sort"];

        // dump($input);
        $banners = new BannersModel();
        $banners->data($input);
        $ob = $banners->save();
        if($ob){
            $Url=popBox('success','添加成功!');
            $this->redirect($Url);
        }


    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        //
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
    }
}
