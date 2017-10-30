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
}
