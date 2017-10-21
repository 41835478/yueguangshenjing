<?php

namespace app\admin\controller;

use think\Controller;
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
           if(input('?file.show_pic')){
               $files = $request->file('show_pic');
               $info = $files->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
               if ($info) {
                   $saveName = $info->getSaveName();
                   $arr = explode("\\", $saveName);
                   $str = implode('/', $arr);
                   $date['show_pic']='/admin/goods/'.$str;
               } else {
                   $url=popBox('warning',$files->getError());
                   $this->redirect($url);
               }
           }else{
               $url=popBox('error','请上传商品展示图');
               $this->redirect($url);
           }
           $res=model('Goods')->data($date)->save();
           if($res){
               $url=popBox('success','添加商品成功');
               $this->redirect($url);
           }else{
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

   public function editShowPic(Request $request)//修改商品展示图
   {
       if(input('?file.show_pic')){
           $files = $request->file('show_pic');
           $info = $files->move(ROOT_PATH . 'public' . DS . 'resource'.DS.'admin'.DS.'goods');
           if ($info) {
               $saveName = $info->getSaveName();
               $arr = explode("\\", $saveName);
               $str = implode('/', $arr);
               $date['show_pic']='/admin/goods/'.$str;
               $date['updated_at']=time();
               $res=model('Goods')->where(['id'=>$request->post('id')])->setField($date);
               if($res){
                   $url=popBox('success','修改商品展示图成功');
               }else{
                   $url=popBox('error','修改商品展示图失败');
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
       if(Goods::destroy($id)){
           return json(['status'=>true,'message'=>'删除成功']);
       }
       return json(['status'=>false,'message'=>'删除失败']);
   }
}
