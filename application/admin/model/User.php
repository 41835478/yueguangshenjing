<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
   // 确定链接表名
    protected $table = 'ygsj_user';

    const LEVEL_ONE = "1";#普通会员
    const LEVEL_TWO = "2";#消费会员
    const LEVEL_THREE = "3";#县级市
    const LEVEL_FOUR = "4";#地级市
    const LEVEL_FIVE = "5";#省会城市
    const LEVEL_SIX = "6";#一线城市

    public function accountRecord()
    {
        return $this->hasMany("AccountRecordModel","user_id","id");
    }
    public function withdraw()
    {
        return $this->hasMany("WithdrawalsModel","user_id","id");
    }
    public function upUser($pid)
    {
        return $this->where(["id"=>$pid])->find();
    }
    public function downUser($id)
    {
        return $this->where(["pid"=>$id])->find();
    }
}