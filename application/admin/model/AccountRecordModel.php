<?php

namespace app\admin\model;

use think\Model;

class AccountRecordModel extends Model
{
    //
    // 确定链接表名
    protected $autoWriteTimestamp=true;
    protected $createTime='created_at';
    protected $updateTime = false;
    protected $update = ['updated_at'];
    protected $table = 'ygsj_account_record';

    const TYPE_ONE = "1";#分销奖
    const TYPE_TWO = "2";#招商升级奖
    const TYPE_THREE = "3";#招商销售奖
    const TYPE_FOUR = "4";#安装红包
    const TYPE_FIVE = "5";#购买商品
    const TYPE_SIX = "6";#提现
    const TYPE_SEVEN = "7";#TODO
    const TYPE_EIGHT = "8";#充值
}
