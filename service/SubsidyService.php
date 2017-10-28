<?php
namespace Service;

use app\admin\model\Config;
use app\admin\model\OrderModel;
use app\admin\model\User;
use think\Db;
use think\Exception;
use app\admin\model\AccountRecordModel;
use think\Log;

/**
 * 补助
 */
class ThreeDistribution{
    public $order;
    public function __construct()
    {
        $this->order = new OrderModel();
    }

    public function  subsidy($orderid)
    {
        $order = OrderModel::get($orderid);

        $account = new AccountRecord();
        #店面
        if($order->sign == 2){
            $user_storefront = User::get($order->shop_id);#查询店面

            if($user_storefront->level == "7"){#一级 补助100 直属代理商余额减去100
                $user_storefront->setInc("account",Config::get(11)->value);
                $account->setAccountRecord($order->shop_id,"一级店面补助",
                    9,1,Config::get(11)->value);

                if($user_storefront->agency_id != 0){#为0  系统直属店面
                    $user_agent = User::get($user_storefront->agency_id);

                    $user_agent->setInc("account",(Config::get(9)->value - Config::get(11)->value);
                    $account->setAccountRecord($order->agency_id,"店面补助直属代理扣款",
                        11,1,(Config::get(9)->value - Config::get(11)->value));
                }

            }
            if($user_storefront->level == "8"){#二级 补助50 直属代理商余额减去50
                $user_storefront->setInc("account",Config::get(12)->value);
                $account->setAccountRecord($order->shop_id,"二级店面补助",
                    9,1,Config::get(11)->value);

                if($user_storefront->agency_id != 0){#为0  系统直属店面
                    $user_agent = User::get($user_storefront->agency_id);
                    $user_agent->setInc("account",(Config::get(9)->value - Config::get(12)->value));
                    $account->setAccountRecord($order->agency_id,"店面补助直属代理扣款",
                        11,2,(Config::get(9)->value - Config::get(12)->value));
                }
            }

        }
        #代理商
        if($order->sign == 1 && $order->send_id != ""){#代理发货奖 奖励50
            $user_agent = User::get($order->send_id);
            $user_agent->setInc("account",Config::get(10)->value);
            $account->setAccountRecord($order->send_id,"代理商发货奖",
                12,2,Config::get(10)->value);
            if($this->umbrella($orderid)){

            }

        }
    }
    #查询伞下代理商
    public function  umbrella($id){
        $user_list = getUpUser(User::all(),$id);
        $account = new AccountRecord();
        for($i=0;$i<count($user_list);$i++){
            if(in_array(User::get($user_list[$i])->level,[3,4,5,6])){
                User::get($user_list[$i])->setInc("account",Config::get(9)->value);
                $account->setAccountRecord($id,"代理商伞下购货",
                    11,2,(Config::get(9)->value - Config::get(12)->value));
                if($account){
                    return true;
                }
            }
        }
    }
}