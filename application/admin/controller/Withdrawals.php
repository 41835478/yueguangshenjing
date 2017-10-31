<?php

namespace app\admin\controller;

use app\admin\model\WithdrawalsModel;
use Service\AccountRecord;

class Withdrawals extends Base
{
    public function index()
    {
        $withdraw = WithdrawalsModel::order("created_at","desc")->select();
        return $this->fetch("withdraw/index",["withdraw"=>$withdraw]);
    }
    public function refuse($withdrawId)
    {
        $withdraw = WithdrawalsModel::get($withdrawId);
        $withdraw->status = 3;
        $withdraw->save();
        if($withdraw){
            $account = new AccountRecord();
            $account->setAccountRecord($withdraw->user_id,"提现驳回",6,1,$withdraw->money);
            if($account){
                $Url=popBox('success','驳回成功!');
                $this->redirect($Url);
            }
        }
    }
    public function adopt($allId)

    {
        $withdrawId = explode(',',rtrim($allId,','));
        $where["id"] = ["IN",$withdrawId];
        $data = WithdrawalsModel::where($where)->update(["status"=>2]);#TODO
        if($data){
            $account = new AccountRecord();
            for($i=0;$i<count($withdrawId);$i++){
                $withdrawOne = WithdrawalsModel::get($withdrawId[$i]);
                $account->setAccountRecord($withdrawId[$i],"提现成功",6,2,$withdrawOne->reality_money);
            }
            if($account){
                return json(["status"=>0]);
            }
        }
    }
}
