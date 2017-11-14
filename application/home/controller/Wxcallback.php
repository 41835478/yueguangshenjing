<?php

namespace app\home\controller;

use app\admin\model\AccountRecordModel;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Wxcallback extends Controller
{
    public function paynotify()//微信支付回调
    {
        $xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        $xmlArr = model('WxPayService','service')->xmlToArray($xml);
        if( ($xmlArr['result_code'] == 'SUCCESS') || ($xmlArr['result_code'] == 'SUCCESS') ){
//             file_put_contents('./log.txt',file_get_contents('./log.txt')."\n".'支付成功');
            $result = $xmlArr;
            $result = model('WxPayService','service')->wxorderquery($xmlArr['transaction_id']);
            if($result != 'ERROR'){
                $order=model('admin/OrderModel')->where(['order_code'=>strval($result['out_trade_no'])])->find();//修改订单
                if($order&&$order->status==1){//&&($order->price==$total_amount)
                    Db::startTrans();
                    try {
                        $order->status=2;
                        $order->flag=2;
                        $order->trade_no = $xmlArr['transaction_id'];
                        if ($order->save()) {
                            $res=$this->writeRecord($order->user_id,$order->price);
                            if ($res) {
                                $serviceDb=model('InstallFeeService','service');
                                $res3=$serviceDb->index($order->id);
                                if($res3){
                                    $user=model('admin/User')->get($order->user_id);
                                    if($user->level!=2){
                                        $user->level=2;
                                        if($user->save()){
                                            Db::commit();
                                            echo 'success';
                                        }else{
                                            throw new Exception();
                                        }
                                    }else{
                                        Db::commit();
                                        echo 'success';
                                    }
                                }else{
                                    throw new Exception();
                                }
                            } else {
                                throw new Exception();
                            }
                        } else {
                            throw new Exception();
                        }
                    } catch (Exception $e) {
                        Db::rollback();
                        echo 'fail';
                    }
                }else{
                    echo 'success';
                }
            }
            echo 'fail';
        }else{
            exit("fail");
        }
    }

    public function writeRecord($user_id,$money)//写入记录
    {
        $date['user_id']=$user_id;
        $date['record_info']='购买商品';
        $date['type']=AccountRecordModel::TYPE_FIVE;
        $date['status']=2;
        $date['money']=$money;
        $res=model('admin/AccountRecordModel')->data($date)->save();
        if($res){
            return true;
        }
        return false;
    }
}
