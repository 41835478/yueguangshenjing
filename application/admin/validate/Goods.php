<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/21
 * Time: 10:11
 */
namespace app\admin\validate;
use think\Validate;

class Goods extends Validate
{
    protected $rule=[
        'name'=>'require',
        'describes'=>'require',
        'goods_price'=>'require|gt:0|number',
        'goods_brand'=>'require',
        'goods_color'=>'require',
    ];
    protected $message=[
        'name.require'=>'商品名称不能为空',
        'describes.require'=>'商品描述不能为空',
        'goods_price.require'=>'商品价格不能为空',
        'goods_price.gt'=>'商品价格必须大于0',
        'goods_price.number'=>'商品价格必须为数字',
        'goods_brand.require'=>'商品品牌不能为空',
        'goods_color.require'=>'商品颜色不能为空',
    ];
}