<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Goods extends Controller
{
    public function goodsInfo($id,$num=1)//商品详情
    {
        $goods=model('admin/Goods')->get($id);
        return view('goods/goodsInfo',['goods'=>$goods,'num'=>$num]);
    }

    public function checkGoodsNum()//检查商品数量
    {
        $date=input('post.');
        if($date['num']>0){
            session('goods_id',$date['id'],'home');
            session('num',$date['num'],'home');
            return json(['status'=>200,'message'=>'数据合法']);
        }
        return json(['status'=>201,'message'=>'非法数据']);
    }
}
