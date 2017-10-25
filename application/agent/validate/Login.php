<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/25
 * Time: 14:32
 */
namespace app\agent\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule=[
        'mobile' => ['require', 'regex' => '/^1[34578]\d{9}$/'],
        'login_pwd' => ['require', 'length' => '6,16'],
        'code' => 'require'
    ];

    protected $message = [
        'mobile.require' => '管理员账号不能为空',
        'mobile.regex' => '登录账号格式错误',
        'login_pwd.require' => '登录密码不能为空',
        'login_pwd.length' => '请输入6到16位的登录密码',
        'code.require' => '验证码不能为空',
    ];
}