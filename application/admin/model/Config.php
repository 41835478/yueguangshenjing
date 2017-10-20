<?php
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class Config extends Model
{
	use SoftDelete;
	protected $deleteTime = 'deleted_at';
   // 确定链接表名
    protected $table = 'ygsj_config';
}