<?php

namespace app\admin\model;

use think\Model;

class WithdrawalsModel extends Model
{
    //
    protected $table = 'ygsj_withdrawals';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    const STATUS_ONE = "1";#待审核
    const STATUS_TWO = "2";#同意
    const STATUS_THREE = "3";#驳回

    public function user()
    {
        return $this->belongsTo("User","user_id");
    }
}
