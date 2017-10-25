<?php

namespace app\admin\model;

use think\Model;

class RearviewRecordModel extends Model
{
    //
    protected $table = 'ygsj_rearview_record';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
