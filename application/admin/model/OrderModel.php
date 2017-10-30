<?php

namespace app\admin\model;

use think\Model;

class OrderModel extends Model
{
    //
    protected $table = 'ygsj_order';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    const STATUS_ONE = "1";#待付款
    const STATUS_TWO = "2";#待发货
    const STATUS_THREE = "3";#待收货
    const STATUS_FOUR = "4";#已完成

    public function user()
    {
        return $this->belongsTo('User','send_id');
    }

    public function shop()
    {
        return $this->belongsTo('User','shop_id');
    }

    public function getUser()
    {
        return $this->belongsTo('User','user_id');
    }
}
