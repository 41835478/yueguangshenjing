<?php

namespace app\admin\controller;

use app\admin\model\AccountRecordModel;
use app\admin\model\User;
use Service\AccountRecord;
use think\Controller;
use think\Request;

class Users extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

        $user = new User();
        if($request->has('mobile','param',true)){
            $user->where('mobile','like','%'.$request->param('mobile').'%');
        }
        if($request->has('nickname','param',true)){
            $user->where('nickname','like','%'.$request->param('nickname').'%');
        }
        if($request->has('from_phone','param',true)){#推荐人手机号
            $fromUser = $user->where(["mobile"=>$request->param('from_phone')])->column("id");
            $where["pid"] = ["IN",$fromUser];
            $user->where($where);
        }

        if($request->has('level','param',true)){
            $user->where(['level'=>$request->param('level')]);
        }
        if($request->has('start','param',true)){#开始日期
            $user->where('created_at','>=',$request->input('start'));
        }
        if($request->has('end','param',true)){#结束日期
            $user->where('created_at','<=',$request->input('end'));
        }
        $data=$user->paginate(config("page"),false, [
            'query' => Request::instance()->param(),//不丢失已存在的url参数
        ]);
        foreach ($data as $v){
            $v['pid'] = User::get($v['pid'])["mobile"];
            $v["level"] = config('level')[$v["level"]];
            $v["created_at"] = date("Y-m-d H:i:s",$v["created_at"]);
        }

        $page=$this->getPage($data);

        return $this->fetch("users/index",["currentPage"=>$page['currentPage'],
            "total"=>$page['total'], "data"=>$data,"render"=>$page['page']]);
    }

    public function getPage($object)//分页
    {
        if($object){
            $pages['currentPage']= $object->currentPage();
            $pages['total'] = $object->total();
            $pages['page'] = $object->render();
        }else{
            $pages['currentPage']=$pages['total']=$pages['page']=0;
        }
        return $pages;
    }

    public function recharge()
    {
        $input = Request::instance()->only("is_add,recharge_id,num");
        $user = User::get($input["recharge_id"]);
        $account = new AccountRecord();

        if($input["is_add"] == "1"){
            $user->setInc("account",$input["num"]);
            $account->setAccountRecord($input["recharge_id"],"系统充值",
                AccountRecordModel::TYPE_EIGHT,1,$input["num"]);

            $Url = popBox("success","系统充值成功");
            $this->redirect($Url);
        }else{
            $user->setDec("account",$input["num"]);

            $account->setAccountRecord($input["recharge_id"],"系统扣款",
                AccountRecordModel::TYPE_EIGHT,2,$input["num"]);

            $Url = popBox("success","系统扣款成功");
            $this->redirect($Url);

        }
    }

}
