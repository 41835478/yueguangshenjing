<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 8:29
 */
namespace app\admin\validate;
use think\Validate;

class Video extends Validate
{
    protected $rule=[
        'title'=>'require',
        'video_path'=>'require'
    ];

    protected $message=[
        'title.require'=>'视频标题不能为空',
        'video_path.require'=>'视频源地址为空，请尝试重新上传视频',
    ];
}