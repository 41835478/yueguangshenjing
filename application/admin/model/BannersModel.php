<?php

namespace app\admin\model;

use think\Model;

class BannersModel extends Model
{

    protected $table = 'ygsj_banners';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
