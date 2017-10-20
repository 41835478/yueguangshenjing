<?php

namespace app\admin\model;

use think\Model;

class ContentsModel extends Model
{
    //
    protected $table = 'ygsj_contents';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
