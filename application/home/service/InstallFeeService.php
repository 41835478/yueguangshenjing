<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 8:31
 */
namespace app\home\service;
use app\admin\model\AccountRecordModel;
use think\Db;
use think\Exception;
use think\Model;

class InstallFeeService extends Model
{
    public function index($order_id)
    {
        $order=model('admin/OrderModel')->get($order_id);
        return $this->writeRecord($order->user_id,50*$order->num);
    }

    public function writeRecord($user_id,$money)//写入记录
    {
        $date['user_id']=$user_id;
        $date['record_info']='安装费补助'.$money.'元';
        $date['type']=intval(AccountRecordModel::TYPE_FOUR);
        $date['status']=1;
        $date['money']=$money;
        $res=model('AccountRecord')->data($date)->save();
        Db::startTrans();
        try{
            if($res){
                $user=model('admin/User')->get($user_id);
                $user->account=$user->account+$money;
                $user->install_red=$user->install_red+$money;
                if($user->save()){
                    Db::commit();
                    return true;
                }else{
                    throw new Exception();
                }
            }else{
                throw new Exception();
            }
        }catch(Exception $e){
            Db::rollback();
            return false;
        }
    }
}