<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/23
 * Time: 12:04
 */
namespace app\home\widget;
use think\Controller;

class IndexGoods extends Controller
{
    public function index()//遍历首页商品
    {
        $data=array();
        $data=model('admin/Goods')->field(['id','name','main_pic','goods_price','describes'])->where(['status'=>1])->select();
        $this->assign('data',$data);
        return $this->fetch('index/widgetIndexGoods');
    }

    public function goodsSmallPic($id)
    {
        $data=model('admin/GoodsInfo')->where(['goods_id'=>$id])->select();
        $this->assign('data',$data);
        return $this->fetch('goods/goodsSmallPic',['data'=>$data]);
    }
}