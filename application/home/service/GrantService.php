<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28
 * Time: 11:34
 */
namespace app\home\service;
use think\Model;

class GrantService extends Model
{
    /*代码中没有给用户加安装费*/

    public function index($order_id)
    {

    }

    public function businessAward($order_id)//招商奖
    {
        $order=model('admin/OrderModel')->get($order_id);
        if($order->sign==1){//说明该订单是代理商销售

        }
    }

    public function loopAgent($user_id)//递归向上查代理商
    {
        $pid=model('admin/User')->where(['id'=>$user_id])->value('pid');
        if($pid){

        }
        return false;
    }
}