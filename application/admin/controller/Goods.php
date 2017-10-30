<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Goods extends Base
{
   public function index()//加载添加商品视图
   {
        return view('goods/index');
   }

   public function addGoods(Request $request)//添加商品操作
   {
       $date=$request->post();
       $validate=validate('Goods');
       if(!$validate->check($date)){
           $url=popBox('warning',$validate->getError());
           $this->redirect($url);
       }else{
           if(input('?file.main_pic')){
               $file = $request->file('main_pic');
               $info = $file->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
               if ($info) {
                   $saveName = $info->getSaveName();
                   $arr = explode("\\", $saveName);
                   $str = implode('/', $arr);
                   $date['main_pic']='/admin/goods/'.$str;
               } else {
                   $url=popBox('warning',$file->getError());
                   $this->redirect($url);
               }
           }else{
               $url=popBox('error','请上传商品主图');
               $this->redirect($url);
           }
           unset($_FILES['main_pic']);
           if(input('?file.small_pic')){
               $files = $request->file('small_pic');
               foreach($files as $v){
                   $info = $v->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
                   if ($info) {
                       $saveName = $info->getSaveName();
                       $arr = explode("\\", $saveName);
                       $str = implode('/', $arr);
                       $img[]='/admin/goods/'.$str;
                   } else {
                       $url=popBox('warning',$file->getError());
                       $this->redirect($url);
                   }
               }
           }else{
               $url=popBox('error','请上传商品展示图');
               $this->redirect($url);
           }
           Db::startTrans();
           try{
               $date['created_at']=time();
               $res=model('Goods')->insertGetId($date);
               if($res){
                   foreach($img as $v){
                       $data[]['goods_id']=$res;
                       $data[]['small_pic']=$v;
                   }
                   $res2=model('GoodsInfo')->saveAll($data);
                   if($res2){
                       Db::commit();
                       $url=popBox('success','添加商品成功');
                       $this->redirect($url);
                   }else{
                       throw new Exception();
                   }
               }
           }catch(Exception $e){
               Db::rollback();
               $url=popBox('error','添加商品失败');
               $this->redirect($url);
           }
       }
   }

   public function goodsList()//商品列表
   {
       $data=model('Goods')->select();
       return view('goods/goodsList',['data'=>$data]);
   }

   public function goodsStatus()//修改商品状态
   {
       $date=input('post.');
       if($date['flag']==2){//下架
           $data['status']=2;
       }else{
           $data['status']=1;
       }
       $data['updated_at']=time();
       $res=model('Goods')->where('id',$date['id'])->setField($data);
       if($res){
           return json(['status'=>true,'message'=>'修改商品状态成功']);
       }
       return json(['status'=>false,'message'=>'修改商品状态失败']);
   }

   public function smallPicView($id)
   {
       $data=model('GoodsInfo')->where(['goods_id'=>$id])->select();
       return view('goods/smallPicView',['data'=>$data,'id'=>$id]);
   }

   public function smallDel()//删除商品轮播图
   {
       $id=input('post.id');
       $mod=model('GoodsInfo');
       $goods=$mod->get($id);
       $res=$mod->where('id',$id)->delete();
       if($res){
           unlink('./resource'.$goods->small_pic);
           return json(['status'=>true,'message'=>'删除成功']);
       }
       return json(['status'=>false,'message'=>'删除失败']);
   }

   public function editSmallPic(Request $request)//修改商品展示图
   {
       if(input('?file.small_pic')){
           $files = $request->file('small_pic');
           $info = $files->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
           if ($info) {
               $saveName = $info->getSaveName();
               $arr = explode("\\", $saveName);
               $str = implode('/', $arr);
               $date['small_pic']='/admin/goods/'.$str;
               $date['goods_id']=input('post.id');
               $res=model('GoodsInfo')->data($date)->save();
               if($res){
                   $url=popBox('success','添加商品轮播图成功');
               }else{
                   $url=popBox('error','添加商品轮播图失败');
               }
           } else {
               $url=popBox('warning',$files->getError());
           }
       }else{
           $url=popBox('warning','您没有进行任何操作');
       }
       $this->redirect($url);
   }

   public function editGoods(Request $request)//修改商品
   {
       $date=$request->post();
       $validate=validate('Goods');
       if(!$validate->check($date)){
           $url=popBox('warning',$validate->getError());
           $this->redirect($url);
       }else{
           if(input('?file.main_pic')){
               $file = $request->file('main_pic');
               $info = $file->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
               if ($info) {
                   $saveName = $info->getSaveName();
                   $arr = explode("\\", $saveName);
                   $str = implode('/', $arr);
                   $date['main_pic']='/admin/goods/'.$str;
               } else {
                   $url=popBox('warning',$file->getError());
                   $this->redirect($url);
               }
           }
           $res=model('Goods')->save($date,['id'=>$date['id']]);
           if($res){
               $url=popBox('success','修改商品成功');
               $this->redirect($url);
           }else{
               $url=popBox('error','修改商品失败');
               $this->redirect($url);
           }
       }
   }

   public function delGoods()//商品删除
   {
       $id=input('post.id');
       $goods=model('Goods')->get($id);
       if($goods->delete()){
           return json(['status'=>true,'message'=>'删除成功']);
       }
       return json(['status'=>false,'message'=>'删除失败']);
   }

   public function editGoodsInfo()//修改商品详情
   {
       $date=input('post.');
       if(!$date['content']){
           $url=popBox('error','商品详情不能为空');
           $this->redirect($url);
       }
       $goods=model('Goods')->get($date['id']);
       $goods->content=$date['content'];
       if($goods->save()){
           $url=popBox('error','商品详情修改成功');
       }else{
           $url=popBox('error','商品详情修改失败');
       }
       $this->redirect($url);
   }
}
