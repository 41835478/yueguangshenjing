<?php

namespace app\admin\model;

use think\Model;

class Roles extends Model
{
    protected $autoWriteTimestamp=true;
    protected $createTime='create_at';
    protected $updateTime = false;
    protected $update = ['update_at'];
    protected $table='ygsj_role';
}
