<?php

namespace app\admin\model;

use think\Model;

class Video extends Model
{
    protected $autoWriteTimestamp=true;
    protected $createTime='created_at';
    protected $updateTime = false;
    protected $update = ['updated_at'];
}
