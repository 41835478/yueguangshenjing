<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 10:26
 */
namespace app\home\logic;

use think\Model;

class ConfirmLogin extends Model
{
    protected $config;
    public function __construct($data)
    {
        $this->config=model('admin/Config');
    }

    public function index()
    {
        $this->getOrderStatus();
    }

    public function getOrderStatus()//获取确认收货的订单
    {
        $time=time();
        $data=$list=array();
        $orderData = model('admin/OrderModel')->field(['id', 'user_id', 'created_at'])->where(['status' => 3])->select();
        foreach ($orderData as $v){
            $daysAfter=$this->getDaysAfter($v['created_at'],$this->config->value);
            if($time>=$daysAfter){
                $data[]=$v['id'];
            }
        }
        foreach($data as $v){
            $arr['id']=$v;
            $arr['status']=4;
            $list[]=$arr;
        }
        $res=model('admin/OrderModel')->saveAll($list);
        if($res){
            return true;
        }
        return false;
    }

    /**
     * 返回几天后的时间戳
     *
     * @param $time  时间戳
     * @param $day   天数
     * @return mixed 时间戳
     */
    public function getDaysAfter($time,$day)//获取几天后的时间戳
    {
        return $time + $day * 86400;
    }
}