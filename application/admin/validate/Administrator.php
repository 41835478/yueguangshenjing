<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20
 * Time: 15:58
 */
namespace app\admin\validate;
use think\Validate;

class Administrator extends Validate
{
    protected $rule=[
        'name'=>'require',
        'mobile'=>['require','regex' => '/^1[34578]\d{9}$/'],
        'pwd'=>'require',
        're_pwd'=>'require|confirm:pwd',
    ];

    protected $message=[
        'name.require'=>'管理员名称不能为空',
        'mobile.require'=>'管理员手机号不能为空',
        'mobile.regex'=>'管理员手机号格式错误',
        'pwd.require'=>'管理员密码不能为空',
        're_pwd.require'=>'确认密码不能为空',
        're_pwd.confirm'=>'两次输入密码不一致',
    ];
}