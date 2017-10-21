<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Goods extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp=true;
    protected $createTime='created_at';
    protected $deleteTime = 'deleted_at';
    protected $updateTime = false;
    protected $update = ['updated_at'];
    protected $table="ygsj_goods";
}
