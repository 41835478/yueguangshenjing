<?php

namespace app\admin\controller;

use app\admin\model\Config;
use think\Request;

class Configs extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        //
        $configs = Config::all();
        return $this->fetch("configs/index",["configs"=>$configs]);
    }
    public function edit($id)
    {
        //
        $configs = Config::get($id);
        $input = Request::instance()->only("value");

        if($input["value"] < 0 || empty($input["value"])){
            $Url=popBox('error','输入有误');
            $this->redirect($Url);
        }

        $configs->save($input,["id"=>$id]);
        $Url=popBox('success','设置成功');
        $this->redirect($Url);
    }

}
