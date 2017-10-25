<?php

namespace app\admin\model;

use think\Model;

class RearviewModel extends Model
{
    //
    protected $table = 'ygsj_rearview';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
