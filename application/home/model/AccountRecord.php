<?php

namespace app\home\model;

use think\Model;

class AccountRecord extends Model
{
    protected $autoWriteTimestamp=true;
    protected $createTime='created_at';
    protected $updateTime = false;
    protected $update = ['updated_at'];
    protected $table = 'ygsj_account_record';
}
