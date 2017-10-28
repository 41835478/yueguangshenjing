<?php
namespace Service;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use think\Db;
use think\Exception;

/**
 * 三级分佣
 */
class ThreeDistribution{
    public $order;
    public function __construct()
    {
        $this->order = new OrderModel();
    }

    /**
     * 三级分佣
     * @return bool
     */
    public function addThree($orderid)
    {
        $order = OrderModel::get($orderid);
        $user = User::all();
        $prent = getUpUser($user,$order->user_id);
        $configOne =  Config::get(2)->value / 100;
        $configTwo =  Config::get(3)->value / 100;
        $configThree =  Config::get(4)->value / 100;

//        if(isset($prent[1]) && $prent[1]){#1
//            Db::startTrans();
//            try{
//                if(User::where('id',$prent[1])->value('status') == 1){
//                    $resOne = UserModel::where('id',$prent[1])->setInc('account',$orderid->price * $configOne);
//                }
//                if(User::where('id',$prent[2])->value('status') == 1){
//                    $resOne = UserModel::where('id',$prent[2])->setInc('account',$orderid->price * $configTwo);
//                }
//                if(User::where('id',$prent[3])->value('status') == 1){
//                    $resOne = UserModel::where('id',$prent[3])->setInc('account',$orderid->price * $configThree);
//                }
//                Db::commit();
//            }catch (Exception $e){
//                Db::rollback();
//            }
//        }

    }
}