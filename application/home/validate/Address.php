<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/23
 * Time: 18:23
 */
namespace app\home\validate;
use think\Validate;

class Address extends Validate
{
    protected $rule=[
        'name'=>'require',
        'phone'=>['require','regex' => '/^1[34578]\d{9}$/'],
        'area'=>'require',
        'street'=>'require',
        'area_info'=>'require',
    ];
    protected $message=[
        'name.require'=>'收货人名称不能为空',
        'phone.require'=>'联系电话不能为空',
        'phone.regex'=>'联系电话格式错误',
        'area.require'=>'所在地区不能为空',
        'street.require'=>'所在街道不能为空',
        'area_info.require'=>'详细地址不能为空'
    ];
}