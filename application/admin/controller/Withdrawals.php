<?php

namespace app\admin\controller;

use app\admin\model\WithdrawalsModel;
use Service\AccountRecord;
use think\Controller;

class Withdrawals extends Controller
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
                model('user')->setInc('account',$withdraw->money);
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

    public function allowWithdraw()//支付宝提现通过
    {
        $id=input('get.id');
        halt($id);
        $withdraw=model('WithdrawalsModel')->get($id);
        if($withdraw&&($withdraw->status==1)&&($withdraw->reality_money)>0){
            $osn             = $withdraw->alipay_num;//$withDraw->out_biz_no//支付单号
            $payee_account   = $withdraw->	alipay_account;//$payment->number//支付宝账号
            $payee_real_name = $withdraw->alipay_name;//$payment->bankname//绑定支付宝的真实名称
            $amount          = $withdraw->reality_money;//$withDraw->arrival_money//转账金额
            $msg=$this->AlipayFundTransToaccountTransfer($osn,$payee_account,$amount,$payee_real_name);
            if($msg['success']=='1'){
                $find=model('WithdrawalsModel')->where(['alipay_num'=>$msg['out_biz_no']])->find();
                $find->status=2;
                $res=$find->save();
                if($res){
                    $account = new AccountRecord();
                    $account->setAccountRecord($find->user_id,"提现成功",6,2,$find->reality_money);
                    $this->success('转账成功',url('withdrawals/index'));
                }else{
                    $this->error('转账成功，但修改数据失败',url('withdrawals/index'));
                }
            }else{
                $this->error($msg['text'],url('withdrawals/index'));
            }
        }else{
            $this->error('非法提现',url('withdrawals/index'));
        }
    }

    /*
     *该方法为支付宝转账方法
     *osn 交易单号
     *payee_account 收款人帐号
     *amount 转账金额
     *payee_real_name 收款方真实姓名
     */


    public function AlipayFundTransToaccountTransfer($osn,$payee_account,$amount,$payee_real_name){
        $msg = array();
        if(!$osn){
            $msg['success'] = 2;
            $msg['text']    = '交易单号为空';
            return $msg;
        }elseif(!$payee_account){
            $msg['success'] = 2;
            $msg['text']    = '收款人帐号为空';
            return $msg;
        }elseif(!$amount || $amount<0.1){
            $msg['success'] = 2;
            $msg['text']    = '转账金额不能小于0.1';
            return $msg;
        }elseif(!$payee_real_name){
            $msg['success'] = 2;
            $msg['text']    = '收款人姓名为空';
            return $msg;
        }else{
            $aop =model('AopClientService','service');
            $aop->gatewayUrl         = 'https://openapi.alipay.com/gateway.do';
            $aop->appId              = config('Ali_APPID');
            $aop->rsaPrivateKey      = config('RSAPRIVATEKRY');
            $aop->alipayrsaPublicKey = config('ALIPAYRSAPUBLICKEY');
            $aop->apiVersion         = '1.0';
            $aop->signType           = 'RSA';
            $aop->postCharset        = 'UTF-8';
            $aop->format             = 'json';
            $request = model('AlipayFundTransToaccountTransferRequestService','service');
            $request->setBizContent("{" .
                "    \"out_biz_no\":\"".$osn."\"," .
                "    \"payee_type\":\"ALIPAY_LOGONID\"," .
                "    \"payee_account\":\"".$payee_account."\"," .
                "    \"amount\":\"".$amount."\"," .
                "    \"payer_show_name\":\"昆明超光科技有限公司\"," .
                "    \"payee_real_name\":\"".$payee_real_name."\"," .
                "    \"remark\":\"转账备注\"," .
                "    \"ext_param\":\"{\\\"order_title\\\":\\\"用户提现\\\"}\"" .
                "  }");
            $result = $aop->execute ( $request);
            $resultCode = $result->alipay_fund_trans_toaccount_transfer_response->code;
            $out_biz_no=$result->alipay_fund_trans_toaccount_transfer_response->out_biz_no;
            if(!empty($resultCode)&&$resultCode == 10000){
                $msg['success'] = 1;
                $msg['text']    = '支付宝转账成功';
                $msg['out_biz_no']=$out_biz_no;
                return $msg;
            }else{
                $resultMsg  = $result->alipay_fund_trans_toaccount_transfer_response->sub_msg;
                $msg['success'] = 2;
                $msg['text']    = $resultMsg;
                return $msg;
            }
        }
    }
}
