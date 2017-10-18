<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/17
 * Time: 14:56
 */
namespace app\admin\validate;
use think\Validate;

class Login extends Validate
{
    protected $rule=[
        'mobile' => ['require', 'regex' => '/^1[34578]\d{9}$/'],
        'pwd' => ['require', 'length' => '6,16'],
        'captcha' => 'require'
    ];

    protected $message = [
        'mobile.require' => '管理员账号不能为空',
        'mobile.regex' => '登录账号格式错误',
        'pwd.require' => '登录密码不能为空',
        'pwd.length' => '请输入6到16位的登录密码',
        'captcha.require' => '验证码不能为空',
    ];
}